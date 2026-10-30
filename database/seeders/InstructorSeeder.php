<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Instructor;


class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Devika Gurung - Founder, Owner
        Instructor::create([
            'name' => 'Devika Gurung',
            'profession' => 'Founder, Owner',
            'experience' => 25,
            'bio' => 'Devika Gurung was born in Jomsom, Nepal. Inspired by Australian yoga teachers at age 18, she studied Hatha Yoga and Ayurvedic massage in Kathmandu. In 2000 she founded the Nepali Yoga Center in Pokhara, now known as Himalayan Yogini. She later studied at the Indian Yoga Institute and traveled extensively across Europe teaching yoga. After overcoming severe illness through spiritual and self-healing practices, she now shares her healing methods through yoga, meditation, pranayama, mantra, mudra, chakra, and energy work.',
            'specialities' => json_encode(['Hatha Yoga', 'Ayurvedic Massage', 'Shamanic Healing', 'Meditation', 'Self-Healing']),
            'certifications' => json_encode(['Hatha Yoga Certification', 'Ayurvedic Massage Diploma', 'Shamanic Practitioner']),
            'image' => 'instructors/devika_gurung.jpg',
        ]);

        // Shyam Gurung - Co-founder, Senior Teacher
        Instructor::create([
            'name' => 'Shyam Gurung',
            'profession' => 'Co-founder, Senior Teacher',
            'experience' => 16,
            'bio' => 'Shyam Gurung was born in Jomsom, Mustang, Nepal. He first encountered yoga during a Vipassana retreat in 2003. He trained under Devika in 2008 and later completed multiple yoga teacher trainings including RYT200. Shyam combines traditional Nepalese culture, Hatha yoga, and modern yoga teaching approaches. He leads teacher training programs, retreats, and workshops across Asia.',
            'specialities' => json_encode(['Hatha Yoga', 'Retreat Leadership', 'Yoga Teacher Training', 'Sound Healing']),
            'certifications' => json_encode(['RYT-200', 'RYT-500', 'Advanced Yoga Teacher', 'Sound Healing Certification']),
            'image' => 'instructors/shyam_gurung.jpg',
        ]);

        // Mangal - Ex-Buddhist Monk and Healer
        Instructor::create([
            'name' => 'Mangal',
            'profession' => 'Ex-Buddhist Monk and Healer',
            'experience' => 18,
            'bio' => 'Mangal, Devika\'s brother, spent years studying Buddhism in monasteries following both Nyingma and Geluk traditions. He studied Tibetan language and Buddhist philosophy extensively. After returning to Pokhara, he completed Hatha Yoga training and later specialized in sound healing and Reiki-based healing using deity mantras. He teaches chakra meditation, Buddhism courses, sound healing, and energetic self-healing.',
            'specialities' => json_encode(['Chakra Meditation', 'Buddhism', 'Reiki Healing', 'Sound Healing', 'Self-Healing']),
            'certifications' => json_encode(['Buddhist Philosophy Certification', 'Hatha Yoga Teacher', 'Reiki Master', 'Sound Healing Specialist']),
            'image' => 'instructors/mangal.jpg',
        ]);

        // Surendra - Asana Teacher
        Instructor::create([
            'name' => 'Surendra',
            'profession' => 'Asana Teacher',
            'experience' => 8,
            'bio' => 'Surendra was born in Mustang, Nepal and spent part of his childhood as a monk. After leaving monastic life, he studied Sociology in Pokhara and later completed his 200-hour yoga teacher training at Himalayan Yogini in 2016. He now teaches Traditional Hatha, gentle Ashtanga flow, and Yin Yoga.',
            'specialities' => json_encode(['Ashtanga Yoga', 'Hatha Yoga', 'Yin Yoga']),
            'certifications' => json_encode(['RYT-200', 'Hatha Yoga Teacher', 'Yin Yoga Specialist']),
            'image' => 'instructors/surendra.jpg',
        ]);

        // Pema - Massage and Buddhism Teacher
        Instructor::create([
            'name' => 'Pema',
            'profession' => 'Massage and Buddhism Teacher',
            'experience' => 12,
            'bio' => 'Pema was born in Kathmandu and studied Buddhism in Tibetan schools in Nepal and India. She received teachings connected to the Dalai Lama and has worked behind the scenes at Himalayan Yogini for many years, helping provide Ayurvedic yogic meals for students. She later studied Ayurvedic massage in Kathmandu and now teaches and provides Ayurvedic massage therapies.',
            'specialities' => json_encode(['Ayurvedic Massage', 'Buddhism', 'Healing', 'Ayurvedic Food']),
            'certifications' => json_encode(['Ayurvedic Massage Diploma', 'Buddhist Studies Certificate', 'Ayurvedic Nutrition Specialist']),
            'image' => 'instructors/pema.jpg',
        ]);

        // Pradeep - Ayurveda Practitioner
        Instructor::create([
            'name' => 'Pradeep',
            'profession' => 'Ayurveda Practitioner',
            'experience' => 10,
            'bio' => 'Pradeep studied Ayurveda at Nepal Sanskrit University, healthcare management at Pokhara University, and Yogic Science at Tribhuvan University. He has worked in government Ayurveda hospitals and Ayurveda health centers in Mustang. He now works with Himalayan Yogini teaching Ayurveda practices and Yoga Philosophy.',
            'specialities' => json_encode(['Ayurveda', 'Yoga Philosophy', 'Healthcare Management', 'Yogic Science']),
            'certifications' => json_encode(['Bachelor of Ayurveda', 'Healthcare Management Certificate', 'Yogic Science Diploma']),
            'image' => 'instructors/pradeep.jpg',
        ]);
    }
}
