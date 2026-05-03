# Payoneer Checkout Integration - Backend Setup Guide

## Overview
This guide covers the complete setup for Payoneer Checkout (HOSTED) integration in your Laravel backend.

---

## ✅ What's Already Done

### 1. Database Migration ✓
- Added Payoneer fields to `payments` table:
  - `payment_gateway` (stripe/payoneer)
  - `payoneer_long_id` (unique session identifier)
  - `payoneer_transaction_id` (transaction reference)
  - `payoneer_redirect_url` (hosted checkout URL)

### 2. Service Layer ✓
- Created `app/Services/PayoneerService.php`
  - Handles Payoneer LIST API communication
  - Creates payment sessions
  - Verifies webhook signatures
  - Maps Payoneer statuses to internal statuses

### 3. Controller ✓
- Created `app/Http/Controllers/payment/PayoneerController.php`
  - `createSession()` - Creates payment session
  - `handleWebhook()` - Processes Payoneer notifications
  - `getPaymentStatus()` - Checks payment status

### 4. Routes ✓
- Added to `routes/api.php`:
  - `POST /api/payoneer/session` - Create payment session
  - `POST /api/payoneer/notifications` - Webhook endpoint
  - `GET /api/payoneer/status/{paymentId}` - Get payment status

### 5. Configuration ✓
- Updated `config/services.php` with Payoneer config
- Updated `.env.example` with Payoneer variables

---

## 🔧 What You Need to Do Now

### Step 1: Configure Environment Variables

Open your `.env` file and add these Payoneer credentials:

```bash
# Payoneer Configuration
PAYONEER_ENV=sandbox                    # Change to 'live' for production
PAYONEER_MERCHANT_CODE=your_merchant_code_here
PAYONEER_PAYMENT_TOKEN=your_payment_token_here
PAYONEER_DIVISION=your_division_here
PAYONEER_WEBHOOK_SECRET=your_custom_secret_here
FRONTEND_URL=http://localhost:3000      # Your Next.js frontend URL
```

#### Where to Get These Values:

1. **PAYONEER_MERCHANT_CODE**: Your Payoneer merchant/store code
2. **PAYONEER_PAYMENT_TOKEN**: Your Payoneer Payment API token (acts as password for Basic Auth)
3. **PAYONEER_DIVISION**: Your division/brand identifier (optional, can be empty)
4. **PAYONEER_WEBHOOK_SECRET**: Create a strong random string (e.g., `openssl rand -hex 32`)
5. **FRONTEND_URL**: Your Next.js app URL (production URL when deploying)

### Step 2: Test the Integration

#### A. Test Payment Session Creation

```bash
curl -X POST http://localhost:8000/api/payoneer/session \
  -H "Content-Type: application/json" \
  -d '{
    "orderId": "TEST-001",
    "amount": 50.00,
    "full_name": "John Doe",
    "email": "john@example.com",
    "phone_number": "+1234567890",
    "description": "Test Booking Payment"
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "payment_id": 1,
  "invoice_number": "INV-XXXXX",
  "redirect_url": "https://sandbox.oscato.com/...",
  "long_id": "https://api.sandbox.oscato.com/api/lists/..."
}
```

#### B. Test Payment Status

```bash
curl http://localhost:8000/api/payoneer/status/1
```

### Step 3: Configure Payoneer Webhook

In your Payoneer merchant dashboard:

1. Navigate to **Integration Settings** → **Notifications**
2. Set the **Notification URL** to:
   ```
   https://your-backend-domain.com/api/payoneer/notifications
   ```
3. Add a **Custom Header**:
   - Header Name: `X-Payoneer-Webhook-Secret`
   - Header Value: `[Your PAYONEER_WEBHOOK_SECRET from .env]`

### Step 4: Update APP_URL in .env

Make sure your `APP_URL` is set correctly:

```bash
# Development
APP_URL=http://localhost:8000

# Production (when deploying)
APP_URL=https://your-backend-domain.com
```

This is crucial because the webhook notification URL is built using `APP_URL`.

---

## 📡 API Endpoints Reference

### 1. Create Payment Session
**Endpoint:** `POST /api/payoneer/session`

**Request Body:**
```json
{
  "orderId": "string (required)",
  "amount": "number (required, min: 0.01)",
  "full_name": "string (required)",
  "email": "string (required, valid email)",
  "phone_number": "string (required)",
  "description": "string (optional)",
  "isAdvancePayment": "boolean (optional)",
  "advancePercentage": "number (optional, 0-100)",
  "fullAmount": "number (optional)"
}
```

**Response:**
```json
{
  "success": true,
  "payment_id": 1,
  "invoice_number": "INV-XXXXX",
  "redirect_url": "https://...",
  "long_id": "https://..."
}
```

### 2. Webhook Notification
**Endpoint:** `POST /api/payoneer/notifications`

**Headers Required:**
- `X-Payoneer-Webhook-Secret`: Your webhook secret

**Automatic Processing:**
- Verifies webhook signature
- Updates payment status
- Updates associated booking status
- Logs all webhook events

### 3. Get Payment Status
**Endpoint:** `GET /api/payoneer/status/{paymentId}`

**Response:**
```json
{
  "success": true,
  "payment": {
    "id": 1,
    "invoice_number": "INV-XXXXX",
    "amount": 50.00,
    "currency": "USD",
    "status": "succeeded",
    "payment_gateway": "payoneer",
    "paid_at": "2026-01-12T10:30:00.000000Z",
    "created_at": "2026-01-12T10:25:00.000000Z"
  }
}
```

---

## 🔄 Payment Flow

```
1. Frontend calls POST /api/payoneer/session
   ↓
2. Backend creates Payment record (status: pending)
   ↓
3. Backend calls Payoneer LIST API
   ↓
4. Backend returns redirect_url to frontend
   ↓
5. Frontend redirects user to Payoneer hosted checkout
   ↓
6. User completes payment on Payoneer
   ↓
7. Payoneer sends webhook to /api/payoneer/notifications
   ↓
8. Backend verifies webhook and updates payment status
   ↓
9. Backend updates booking status (if exists)
   ↓
10. Payoneer redirects user to frontend success/cancel page
```

---

## 🔐 Security Features

1. **Webhook Verification**: Custom header secret validation
2. **Rate Limiting**: 
   - Session creation: 5 requests/minute
   - Status check: 10 requests/minute
3. **HTTPS Required**: For production webhooks
4. **Basic Auth**: Payoneer API uses HTTP Basic Authentication
5. **Database Transactions**: Atomic payment + booking updates

---

## 🐛 Troubleshooting

### Issue: "Failed to create Payoneer session"
**Solution:**
- Check your `PAYONEER_MERCHANT_CODE` and `PAYONEER_PAYMENT_TOKEN`
- Verify you're using the correct environment (sandbox/live)
- Check Laravel logs: `storage/logs/laravel.log`

### Issue: Webhook not receiving notifications
**Solution:**
- Ensure your backend is publicly accessible (use ngrok for local testing)
- Verify webhook URL in Payoneer dashboard
- Check custom header is set correctly
- Review webhook logs in `storage/logs/laravel.log`

### Issue: Payment status not updating
**Solution:**
- Check webhook secret matches between .env and Payoneer dashboard
- Verify database transaction is not failing
- Check if payment record exists with correct `payoneer_long_id`

---

## 📝 Testing with ngrok (Local Development)

For local webhook testing:

```bash
# Start ngrok
ngrok http 8000

# Use the ngrok URL in Payoneer dashboard
https://xxxx-xx-xx-xxx-xxx.ngrok.io/api/payoneer/notifications
```

---

## 🚀 Production Deployment Checklist

- [ ] Set `PAYONEER_ENV=live` in production .env
- [ ] Update `APP_URL` to production backend URL
- [ ] Update `FRONTEND_URL` to production frontend URL
- [ ] Configure webhook URL in Payoneer dashboard (production)
- [ ] Test payment flow end-to-end
- [ ] Monitor logs for any errors
- [ ] Set up error alerting (optional)

---

## 📊 Database Schema

The `payments` table now includes:

```sql
payment_gateway VARCHAR(255) DEFAULT 'stripe'
payoneer_long_id VARCHAR(255) NULLABLE UNIQUE
payoneer_transaction_id VARCHAR(255) NULLABLE
payoneer_redirect_url TEXT NULLABLE
```

---

## 🔗 Useful Links

- **Payoneer Sandbox API**: https://api.sandbox.oscato.com
- **Payoneer Live API**: https://api.live.oscato.com
- **Payoneer Documentation**: Contact your Payoneer account manager

---

## ✅ Quick Verification

Run these checks to ensure everything is set up:

```bash
# 1. Check migration ran successfully
php artisan migrate:status

# 2. Verify routes are registered
php artisan route:list | grep payoneer

# 3. Check .env has all required variables
grep PAYONEER .env

# 4. Test API endpoint is accessible
curl http://localhost:8000/api/payoneer/status/999
```

---

## 📞 Support

If you encounter issues:
1. Check `storage/logs/laravel.log` for detailed error messages
2. Verify all environment variables are set correctly
3. Test with Payoneer sandbox first before going live
4. Contact Payoneer support for API-specific issues

---

**Integration completed by:** Antigravity AI
**Date:** 2026-01-12
**Version:** 1.0
