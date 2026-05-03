<?php

namespace App\Services;

use App\Interfaces\InquiryRepositoryInterface;
use App\Models\Inquiry;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;

class InquiryService
{
    private InquiryRepositoryInterface $inquiryRepository;

    public function __construct(InquiryRepositoryInterface $inquiryRepository)
    {
        $this->inquiryRepository = $inquiryRepository;
    }

    public function getAllInquiries($perPage = 10)
    {
        $inquiries = Inquiry::with('service')->orderBy('created_at', 'desc')->paginate($perPage);

        $inquiries->getCollection()->transform(function ($inquiry) {
            $inquiry->service_name = $inquiry->service ? $inquiry->service->title : null;
            unset($inquiry->service);
            unset($inquiry->service_id);
            unset($inquiry->updated_at);
            return $inquiry;
        });

        return $inquiries;
    }

    public function createInquiry(array $data, $ipAddress, $userAgent)
    {
        $this->validateSpam($data, $ipAddress, $userAgent);

        $data['ip_address'] = $ipAddress;
        $data['user_agent'] = $userAgent;

        $inquiry = $this->inquiryRepository->create($data);

        // Send email
        try {
            Mail::to(config('mail.inquiry_recipient'))->send(new \App\Mail\NewInquiryNotification($data));
        } catch (\Exception $mailException) {
            Log::error('Failed to send inquiry notification email', [
                'error' => $mailException->getMessage(),
                'inquiry_data' => $data,
            ]);
        }

        return $inquiry;
    }

    private function validateSpam(array $data, $ipAddress, $userAgent)
    {
        // 1. Honeypot check
        if (!empty($data['website']) || !empty($data['url'])) {
            throw new Exception('Invalid request', 400); // Silent fail or explicit
        }

        // 2. Duplicate email check
        $recentInquiry = Inquiry::where('email', $data['email'])
            ->where('created_at', '>=', now()->subHours(2))
            ->first();
        if ($recentInquiry) {
            throw new Exception('You can only submit one inquiry per 2 hours with this email address.', 429);
        }

        // 3. Duplicate IP check
        $recentInquiryByIP = Inquiry::where('ip_address', $ipAddress)
            ->where('created_at', '>=', now()->subMinutes(30))
            ->count();
        if ($recentInquiryByIP >= 2) {
            throw new Exception('Too many inquiries from this location. Please try again later.', 429);
        }

        // 4. Content check (hash)
        $messageHash = md5(strtolower(trim($data['message'])));
        $similarMessage = Inquiry::where('created_at', '>=', now()->subHour())
            ->whereRaw('MD5(LOWER(TRIM(message))) = ?', [$messageHash])
            ->first();
        if ($similarMessage) {
            throw new Exception('Duplicate message detected. Please modify your message.', 429);
        }

        // 5. Length and Patterns
        $message = trim($data['message']);
        if (strlen($message) < 10) {
            throw new Exception('Message is too short. Please provide more details.', 422);
        }
        if (preg_match('/(.)\1{10,}/', $message)) {
            throw new Exception('Invalid message format.', 422);
        }

        // 6. Suspicious email
        $emailParts = explode('@', $data['email']);
        if (strlen($emailParts[0]) < 3 || strlen($emailParts[0]) > 50) {
            throw new Exception('Invalid email format.', 422);
        }

        // 7. Daily IP limit
        $dailyInquiries = Inquiry::where('ip_address', $ipAddress)
            ->where('created_at', '>=', now()->subDay())
            ->count();
        if ($dailyInquiries >= 5) {
            throw new Exception('Daily inquiry limit reached. Please try again tomorrow.', 429);
        }
    }

    public function getInquiryById($id)
    {
        $inquiry = Inquiry::with('service')->find($id);
        if ($inquiry) {
            $inquiry->service_name = $inquiry->service ? $inquiry->service->title : null;
            unset($inquiry->service);
            unset($inquiry->service_id);
            unset($inquiry->updated_at);
        }
        return $inquiry;
    }

    public function deleteInquiry($id)
    {
        return $this->inquiryRepository->delete($id);
    }

    public function getTotalInquiriesCount()
    {
        return $this->inquiryRepository->count();
    }
}
