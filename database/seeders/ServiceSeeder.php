<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        // Drop-in Yoga Session
        Service::create([
            'title' => 'Drop-in Yoga Session',
            'slug' => 'drop-in-yoga-session',
            'description' => 'Drop-in yoga session during ongoing yoga retreats.',
            'yoga_type' => 'classes',
            'benefits' => ['Flexibility', 'Strength', 'Relaxation', 'Mind-Body Connection'],
            'instructor_id' => 1,
            'price' => 1500,
            'capacity' => 20,
            'duration' => '60',
            'currency' => 'NPR',
            'class_schedule' => ['Monday', 'Wednesday', 'Friday'],
            'session_time' => '07:00 AM',
            'pricing_options' => [
                [
                    'label' => '1 pax',
                    'price' => 1500,
                    'discount_price' => null,
                    'currency' => 'NPR',
                    'min_pax' => 1,
                    'max_pax' => 1,
                    'description' => 'Individual session',
                    'images' => []
                ],
                [
                    'label' => '2 pax',
                    'price' => 2500,
                    'discount_price' => null,
                    'currency' => 'NPR',
                    'min_pax' => 2,
                    'max_pax' => 2,
                    'description' => 'Pair session',
                    'images' => []
                ],
                [
                    'label' => '3-4 pax',
                    'price' => 4000,
                    'discount_price' => null,
                    'currency' => 'NPR',
                    'min_pax' => 3,
                    'max_pax' => 4,
                    'description' => 'Small group session',
                    'images' => []
                ],
                [
                    'label' => '5-7 pax',
                    'price' => 5000,
                    'discount_price' => null,
                    'currency' => 'NPR',
                    'min_pax' => 5,
                    'max_pax' => 7,
                    'description' => 'Medium group session',
                    'images' => []
                ],
                [
                    'label' => '8+ pax',
                    'price' => 8000,
                    'discount_price' => null,
                    'currency' => 'NPR',
                    'min_pax' => 8,
                    'max_pax' => 50,
                    'description' => 'Large group session',
                    'images' => []
                ]
            ],
            'is_active' => true,
        ]);

        // Ayurvedic Deep Tissue Massage
        Service::create([
            'title' => 'Ayurvedic Deep Tissue Massage',
            'slug' => 'ayurvedic-deep-tissue-massage',
            'description' => 'Therapeutic deep tissue massage combined with Ayurvedic herbal oils and dosha balancing.',
            'yoga_type' => 'wellness',
            'benefits' => ['Muscle Relief', 'Improved Circulation', 'Stress Reduction', 'Detoxification'],
            'instructor_id' => 5,
            'price' => 3500,
            'capacity' => 1,
            'duration' => '60',
            'currency' => 'NPR',
            'class_schedule' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'session_time' => '10:00 AM',
            'pricing_options' => [
                [
                    'label' => '60min',
                    'price' => 3500,
                    'discount_price' => null,
                    'currency' => 'NPR',
                    'min_pax' => 1,
                    'max_pax' => 1,
                    'description' => 'One hour deep tissue massage',
                    'images' => []
                ],
                [
                    'label' => '75min',
                    'price' => 4000,
                    'discount_price' => null,
                    'currency' => 'NPR',
                    'min_pax' => 1,
                    'max_pax' => 1,
                    'description' => 'One hour 15 minutes extended session',
                    'images' => []
                ],
                [
                    'label' => '90min',
                    'price' => 4500,
                    'discount_price' => null,
                    'currency' => 'NPR',
                    'min_pax' => 1,
                    'max_pax' => 1,
                    'description' => 'One hour 30 minutes full body massage',
                    'images' => []
                ]
            ],
            'is_active' => true,
        ]);

        // Sound Bowl Healing (Chakra Healing)
        Service::create([
            'title' => 'Sound Bowl Healing (Chakra Healing)',
            'slug' => 'sound-bowl-healing-chakra',
            'description' => 'Healing session using chakra singing bowls aligned with chakra frequencies.',
            'yoga_type' => 'healing',
            'benefits' => ['Chakra Alignment', 'Energy Balance', 'Deep Relaxation', 'Spiritual Growth'],
            'instructor_id' => 3,
            'price' => 3500,
            'capacity' => 2,
            'duration' => '60',
            'currency' => 'NPR',
            'class_schedule' => ['Tuesday', 'Thursday', 'Saturday'],
            'session_time' => '05:00 PM',
            'pricing_options' => [
                [
                    'label' => '60min - 1 pax',
                    'price' => 3500,
                    'discount_price' => null,
                    'currency' => 'NPR',
                    'min_pax' => 1,
                    'max_pax' => 1,
                    'description' => '7 bowls used',
                    'images' => []
                ],
                [
                    'label' => '60min - 2 pax',
                    'price' => 5000,
                    'discount_price' => null,
                    'currency' => 'NPR',
                    'min_pax' => 2,
                    'max_pax' => 2,
                    'description' => '14 bowls used',
                    'images' => []
                ]
            ],
            'is_active' => true,
        ]);

        // Sound Bath
        Service::create([
            'title' => 'Sound Bath',
            'slug' => 'sound-bath',
            'description' => 'Meditative sound immersion using sound bowls, gong, sea-drum, tingsha, and other instruments.',
            'yoga_type' => 'meditation',
            'benefits' => ['Deep Relaxation', 'Sound Healing', 'Meditation', 'Emotional Release'],
            'instructor_id' => 3,
            'price' => 4000,
            'capacity' => 20,
            'duration' => '60',
            'currency' => 'NPR',
            'class_schedule' => ['Wednesday', 'Saturday', 'Sunday'],
            'session_time' => '06:00 PM',
            'pricing_options' => [
                [
                    'label' => '60min - 2 pax',
                    'price' => 4000,
                    'discount_price' => null,
                    'currency' => 'NPR',
                    'min_pax' => 2,
                    'max_pax' => 2,
                    'description' => 'Pair sound bath session',
                    'images' => []
                ],
                [
                    'label' => '60min - 3-5 pax',
                    'price' => 5000,
                    'discount_price' => null,
                    'currency' => 'NPR',
                    'min_pax' => 3,
                    'max_pax' => 5,
                    'description' => 'Small group sound bath',
                    'images' => []
                ],
                [
                    'label' => '60min - 6-8 pax',
                    'price' => 6500,
                    'discount_price' => null,
                    'currency' => 'NPR',
                    'min_pax' => 6,
                    'max_pax' => 8,
                    'description' => 'Medium group sound bath',
                    'images' => []
                ],
                [
                    'label' => '60min - 9+ pax',
                    'price' => 9000,
                    'discount_price' => null,
                    'currency' => 'NPR',
                    'min_pax' => 9,
                    'max_pax' => 50,
                    'description' => 'Large group sound bath',
                    'images' => []
                ]
            ],
            'is_active' => true,
        ]);

        // Reiki
        Service::create([
            'title' => 'Reiki',
            'slug' => 'reiki',
            'description' => 'Healing technique using energy channeling to support physical and emotional well-being.',
            'yoga_type' => 'healing',
            'benefits' => ['Energy Healing', 'Stress Relief', 'Emotional Balance', 'Spiritual Wellness'],
            'instructor_id' => 3,
            'price' => 3000,
            'capacity' => 1,
            'duration' => '60',
            'currency' => 'NPR',
            'class_schedule' => ['Monday', 'Wednesday', 'Friday'],
            'session_time' => '04:00 PM',
            'pricing_options' => [
                [
                    'label' => '60min',
                    'price' => 3000,
                    'discount_price' => null,
                    'currency' => 'NPR',
                    'min_pax' => 1,
                    'max_pax' => 1,
                    'description' => 'One hour Reiki healing session',
                    'images' => []
                ]
            ],
            'is_active' => true,
        ]);

        // Shirodhara
        Service::create([
            'title' => 'Shirodhara',
            'slug' => 'shirodhara',
            'description' => 'Traditional Ayurvedic therapy involving warm oil poured over the forehead for deep relaxation.',
            'yoga_type' => 'wellness',
            'benefits' => ['Deep Relaxation', 'Mental Clarity', 'Sleep Improvement', 'Stress Relief'],
            'instructor_id' => 6,
            'price' => 4500,
            'capacity' => 1,
            'duration' => '60',
            'currency' => 'NPR',
            'class_schedule' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'session_time' => '03:00 PM',
            'pricing_options' => [
                [
                    'label' => '60min',
                    'price' => 4500,
                    'discount_price' => null,
                    'currency' => 'NPR',
                    'min_pax' => 1,
                    'max_pax' => 1,
                    'description' => 'One hour Shirodhara therapy',
                    'images' => []
                ],
                [
                    'label' => '75min',
                    'price' => 5500,
                    'discount_price' => null,
                    'currency' => 'NPR',
                    'min_pax' => 1,
                    'max_pax' => 1,
                    'description' => 'One hour 15 minutes extended session',
                    'images' => []
                ],
                [
                    'label' => '90min',
                    'price' => 7000,
                    'discount_price' => null,
                    'currency' => 'NPR',
                    'min_pax' => 1,
                    'max_pax' => 1,
                    'description' => 'One hour 30 minutes full therapeutic session',
                    'images' => []
                ]
            ],
            'is_active' => true,
        ]);

        // Ayurvedic Steam Bath
        Service::create([
            'title' => 'Ayurvedic Steam Bath',
            'slug' => 'ayurvedic-steam-bath',
            'description' => 'Steam therapy infused with medicinal herbs and essential oils.',
            'yoga_type' => 'wellness',
            'benefits' => ['Detoxification', 'Improved Circulation', 'Skin Health', 'Immune Boost'],
            'instructor_id' => 6,
            'price' => 3500,
            'capacity' => 1,
            'duration' => '60',
            'currency' => 'NPR',
            'class_schedule' => ['Daily'],
            'session_time' => '09:00 AM',
            'pricing_options' => [
                [
                    'label' => '60min',
                    'price' => 3500,
                    'discount_price' => null,
                    'currency' => 'NPR',
                    'min_pax' => 1,
                    'max_pax' => 1,
                    'description' => 'One hour herbal steam bath',
                    'images' => []
                ]
            ],
            'is_active' => true,
        ]);

        // Vedic Sound Healing Course
        Service::create([
            'title' => 'Vedic Sound Healing Course',
            'slug' => 'vedic-sound-healing-course',
            'description' => 'An immersive course exploring Vedic sound healing practices and sacred chant.',
            'yoga_type' => 'healing',
            'benefits' => ['Stress Reduction', 'Energetic Balancing', 'Inner Stillness'],
            'instructor_id' => 1,
            'price' => 0,
            'capacity' => 20,
            'duration' => '5',
            'currency' => 'NPR',
            'class_schedule' => [],
            'session_time' => null,
            'pricing_options' => [],
            'available_time_slots' => ['1st-5th','7th-11th','13th-17th','19th-23rd'],
            'images' => [],
            'is_active' => true,
        ]);

        // Ayurvedic Detox and Healing
        Service::create([
            'title' => 'Ayurvedic Detox and Healing',
            'slug' => 'ayurvedic-detox-and-healing',
            'description' => 'A therapeutic detox and healing program based on classical Ayurvedic protocols.',
            'yoga_type' => 'wellness',
            'benefits' => ['Detoxification', 'Rejuvenation', 'Digestive Health'],
            'instructor_id' => 1,
            'price' => 0,
            'capacity' => 12,
            'duration' => '5',
            'currency' => 'NPR',
            'class_schedule' => [],
            'session_time' => null,
            'pricing_options' => [],
            'available_time_slots' => ['1st-5th','7th-11th','13th-17th','19th-23rd'],
            'images' => [],
            'is_active' => true,
        ]);

        // Yin Yoga
        Service::create([
            'title' => 'Yin Yoga',
            'slug' => 'yin-yoga',
            'description' => 'Deeply restorative Yin Yoga retreats designed for relaxation and connective tissue work.',
            'yoga_type' => 'classes',
            'benefits' => ['Relaxation', 'Flexibility', 'Joint Health'],
            'instructor_id' => 1,
            'price' => 0,
            'capacity' => 20,
            'duration' => '7',
            'currency' => 'NPR',
            'class_schedule' => [],
            'session_time' => null,
            'pricing_options' => [],
            'available_time_slots' => ['1st-7th','9th-16th','18th-25th','1st-10th','12th-21st'],
            'images' => [],
            'is_active' => true,
        ]);

        // Introduction to Ayurveda
        Service::create([
            'title' => 'Introduction to Ayurveda',
            'slug' => 'introduction-to-ayurveda',
            'description' => 'A short course introducing core Ayurvedic theory, daily routines and practical self-care.',
            'yoga_type' => 'wellness',
            'benefits' => ['Daily Routines', 'Dosha Awareness', 'Self-care Practices'],
            'instructor_id' => 1,
            'price' => 0,
            'capacity' => 20,
            'duration' => '3',
            'currency' => 'NPR',
            'class_schedule' => [],
            'session_time' => null,
            'pricing_options' => [],
            'available_time_slots' => ['1st-3rd','5th-7th','9th-11th','13th-15th','17th-19th','21st-23rd'],
            'images' => [],
            'is_active' => true,
        ]);

        // Ayurveda Massage (same timeline as Vedic Sound Healing)
        Service::create([
            'title' => 'Ayurveda Massage',
            'slug' => 'ayurveda-massage',
            'description' => 'Traditional Ayurvedic massage therapies to balance doshas and release tension.',
            'yoga_type' => 'wellness',
            'benefits' => ['Relaxation','Pain Relief','Rejuvenation'],
            'instructor_id' => 1,
            'price' => 0,
            'capacity' => 6,
            'duration' => '5',
            'currency' => 'NPR',
            'class_schedule' => [],
            'session_time' => null,
            'pricing_options' => [],
            'available_time_slots' => ['1st-5th','7th-11th','13th-17th','19th-23rd'],
            'images' => [],
            'is_active' => true,
        ]);

        // Reiki and Self-healing (same timeline as Vedic Sound Healing)
        Service::create([
            'title' => 'Reiki and Self-healing',
            'slug' => 'reiki-and-self-healing',
            'description' => 'Training and sessions in Reiki energy healing and self-healing techniques.',
            'yoga_type' => 'healing',
            'benefits' => ['Energy Healing','Self-care','Emotional Balance'],
            'instructor_id' => 1,
            'price' => 0,
            'capacity' => 12,
            'duration' => '5',
            'currency' => 'NPR',
            'class_schedule' => [],
            'session_time' => null,
            'pricing_options' => [],
            'available_time_slots' => ['1st-5th','7th-11th','13th-17th','19th-23rd'],
            'images' => [],
            'is_active' => true,
        ]);

        // 300hrs Yoga Teacher Training
        Service::create([
            'title' => '300hrs Yoga Teacher Training',
            'slug' => '300hrs-yoga-teacher-training',
            'description' => 'Intensive 300-hour teacher training covering yoga philosophy, practice and teaching skills.',
            'yoga_type' => 'retreat',
            'benefits' => ['Teaching Skills','Practice Development','Certification'],
            'instructor_id' => 1,
            'price' => 0,
            'capacity' => 30,
            'duration' => '28',
            'currency' => 'NPR',
            'class_schedule' => [],
            'session_time' => null,
            'pricing_options' => [],
            'available_time_slots' => ['1st-28th'],
            'images' => [],
            'is_active' => true,
        ]);

        // Introduction to Meditation
        Service::create([
            'title' => 'Introduction to Meditation',
            'slug' => 'introduction-to-meditation',
            'description' => 'Foundational meditation practices for beginners, building consistency and presence.',
            'yoga_type' => 'meditation',
            'benefits' => ['Mindfulness','Stress Reduction','Concentration'],
            'instructor_id' => 1,
            'price' => 0,
            'capacity' => 30,
            'duration' => '7',
            'currency' => 'NPR',
            'class_schedule' => [],
            'session_time' => null,
            'pricing_options' => [],
            'available_time_slots' => ['1st-7th','9th-15th','17th-23rd'],
            'images' => [],
            'is_active' => true,
        ]);

        // Pranayama (same timeline as Vedic Sound Healing)
        Service::create([
            'title' => 'Pranayama',
            'slug' => 'pranayama-course',
            'description' => 'Breathwork and Pranayama practices for vitality and nervous system regulation.',
            'yoga_type' => 'breathwork',
            'benefits' => ['Respiratory Health','Calmness','Energy Management'],
            'instructor_id' => 1,
            'price' => 0,
            'capacity' => 30,
            'duration' => '5',
            'currency' => 'NPR',
            'class_schedule' => [],
            'session_time' => null,
            'pricing_options' => [],
            'available_time_slots' => ['1st-5th','7th-11th','13th-17th','19th-23rd'],
            'images' => [],
            'is_active' => true,
        ]);

        // Introduction to Yoga (same timeline as Introduction to Meditation)
        Service::create([
            'title' => 'Introduction to Yoga',
            'slug' => 'introduction-to-yoga',
            'description' => 'Beginner friendly yoga course covering basic postures, alignment and practice structure.',
            'yoga_type' => 'classes',
            'benefits' => ['Foundation','Alignment','Consistency'],
            'instructor_id' => 1,
            'price' => 0,
            'capacity' => 30,
            'duration' => '7',
            'currency' => 'NPR',
            'class_schedule' => [],
            'session_time' => null,
            'pricing_options' => [],
            'available_time_slots' => ['1st-7th','9th-15th','17th-23rd'],
            'images' => [],
            'is_active' => true,
        ]);

        // 200hrs Yoga Teacher Training
        Service::create([
            'title' => '200hrs Yoga Teacher Training',
            'slug' => '200hrs-yoga-teacher-training',
            'description' => 'Standard 200-hour teacher training program covering asana, pranayama, anatomy and teaching methodology.',
            'yoga_type' => 'retreat',
            'benefits' => ['Certification','Teaching Skills','Practice Foundation'],
            'instructor_id' => 1,
            'price' => 0,
            'capacity' => 30,
            'duration' => '23',
            'currency' => 'NPR',
            'class_schedule' => [],
            'session_time' => null,
            'pricing_options' => [],
            'available_time_slots' => ['1st-23rd'],
            'images' => [],
            'is_active' => true,
        ]);
    }
}
