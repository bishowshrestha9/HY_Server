<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Blogs;
use Illuminate\Support\Facades\Storage;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blogs = [
            [
                'title' => 'What is Trekking in Nepal?',
                'excerpt' => 'Explains trekking in Nepal, yoga trekking, physical challenges, and differences between trekking, retreats, and yoga courses.',
                'description' => 'Trekking in Nepal is a spiritual and physical adventure through the breathtaking Himalayan landscape. Nepal offers some of the world\'s most scenic trekking routes, combining natural beauty with cultural immersion. Yoga trekking blends the physical challenge of mountain trekking with yoga practice and meditation. This unique combination allows practitioners to connect with nature while advancing their spiritual journey. Trekking challenges the body while building mental resilience. The physical demands of mountain trekking, when combined with yogic practices, create a transformative experience that deepens both physical fitness and spiritual awareness.',
                'image' => null,
                'slug' => 'what-is-trekking-in-nepal',
                'is_active' => true,
            ],
            [
                'title' => 'Difference Between Yoga Retreats, Courses, and Treks',
                'excerpt' => 'Detailed comparison between retreats, structured yoga courses, and trekking experiences.',
                'description' => 'Understanding the difference between yoga retreats, courses, and treks helps you choose the right program for your needs. Yoga retreats are intensive experiences of short duration (typically 3-7 days) focused on relaxation, rejuvenation, and spiritual growth with minimal structured curriculum. Structured yoga courses are comprehensive programs lasting weeks or months with daily classes, philosophy teachings, and progressive learning. Yoga treks combine adventure with yoga practice, offering physical challenge alongside spiritual development. Retreats are ideal for quick rejuvenation, courses for deep learning, and treks for those seeking adventure and personal transformation through nature.',
                'image' => null,
                'slug' => 'difference-between-yoga-retreats-courses-and-treks',
                'is_active' => true,
            ],
            [
                'title' => 'What is Ayurveda?',
                'excerpt' => 'Introduction to Ayurveda, doshas, gunas, seasonal balance, and healing philosophy.',
                'description' => 'Ayurveda, the ancient Indian system of medicine, is one of the world\'s oldest healing sciences. The word "Ayurveda" means "science of life" and represents a comprehensive approach to health based on understanding individual constitution (doshas) and natural balance. The three doshas—Vata, Pitta, and Kapha—represent fundamental energies that govern all bodily and mental functions. Ayurveda emphasizes seasonal living, proper diet, lifestyle, herbal remedies, and therapeutic treatments to maintain health and treat illness. The system recognizes that each person is unique and requires personalized approaches to wellness. Ayurvedic philosophy teaches that health is a state of perfect balance between body, mind, senses, and soul.',
                'image' => null,
                'slug' => 'what-is-ayurveda',
                'is_active' => true,
            ],
            [
                'title' => 'Understanding Chakra Philosophy',
                'excerpt' => 'Explains chakras, meditation, energy systems, and spiritual balance.',
                'description' => 'Chakras are energy centers within the body according to Hindu and Buddhist philosophy. There are seven main chakras aligned along the central energy channel (sushumna nadi) from the base of the spine to the crown of the head. Each chakra corresponds to specific physical, emotional, and spiritual aspects of being. The root chakra (Muladhara) provides stability and grounding. The sacral chakra (Svadhisthana) governs creativity and sensuality. The solar plexus chakra (Manipura) relates to personal power. The heart chakra (Anahata) represents love and compassion. The throat chakra (Vishuddha) governs communication. The third eye chakra (Ajna) relates to intuition. The crown chakra (Sahasrara) represents spiritual connection. Balancing chakras through meditation, yoga, and energy work promotes physical health and spiritual awakening.',
                'image' => null,
                'slug' => 'understanding-chakra-philosophy',
                'is_active' => true,
            ],
            [
                'title' => 'Introduction to Pranayama and Bandha',
                'excerpt' => 'Explains pranayama, bandhas, nadis, breath control, and energy systems.',
                'description' => 'Pranayama is the yogic science of breath control and energy manipulation. The breath is the bridge between body and mind, making pranayama a powerful tool for physical and spiritual development. Bandhas are energy locks used in pranayama to direct and intensify the flow of prana (life force). The three main bandhas are Mula Bandha (root lock), Uddiyana Bandha (abdominal lock), and Jalandhara Bandha (throat lock). Nadis are subtle energy channels through which prana flows. The three most important nadis are Ida (associated with the moon and cooling energy), Pingala (associated with the sun and heating energy), and Sushumna (the central channel). Through pranayama and bandha practice, practitioners awaken and direct kundalini energy, leading to profound spiritual experiences and enhanced physical health.',
                'image' => null,
                'slug' => 'introduction-to-pranayama-and-bandha',
                'is_active' => true,
            ],
            [
                'title' => 'What is Yin Yoga?',
                'excerpt' => 'Describes Yin Yoga, fascia stretching, meditative practice, and connective tissue benefits.',
                'description' => 'Yin Yoga is a slow-paced, meditative style of yoga that targets the deep connective tissues of the body. Unlike more dynamic yoga styles, Yin Yoga involves holding poses for extended periods (3-5 minutes or longer) with minimal muscular engagement. This practice focuses on stretching the fascia—the connective tissue that surrounds and supports muscles, bones, and organs. Regular Yin Yoga practice increases flexibility, improves circulation, enhances joint mobility, and promotes emotional release. The meditative quality of Yin Yoga calms the nervous system, reduces stress, and encourages introspection. It complements more active yoga practices and is particularly beneficial for those seeking balance, deep relaxation, and spiritual growth. Yin Yoga teaches us to move slowly, listen to the body, and embrace stillness.',
                'image' => null,
                'slug' => 'what-is-yin-yoga',
                'is_active' => true,
            ],
            [
                'title' => 'The Healing Power of Sound Bowl Therapy',
                'excerpt' => 'Covers Tibetan singing bowls, chakra healing, sound baths, and vibrational therapy.',
                'description' => 'Sound Bowl Therapy utilizes the ancient power of sound vibrations for healing and spiritual awakening. Tibetan singing bowls, crafted from specific metal alloys, produce resonant tones that deeply relax the nervous system and align the body\'s energy centers. Each bowl is associated with specific chakras and frequencies that promote healing at multiple levels. Sound baths involve lying down in a meditative state while surrounded by the healing vibrations of multiple bowls, gongs, and other sound instruments. The vibrations penetrate cells, release tension, and promote deep relaxation. Sound therapy reduces anxiety, improves sleep, enhances creativity, and facilitates emotional healing. The ancient science of sound healing demonstrates that specific frequencies can restore balance to the body\'s energy system and promote profound wellbeing.',
                'image' => null,
                'slug' => 'the-healing-power-of-sound-bowl-therapy',
                'is_active' => true,
            ],
            [
                'title' => 'What is Reiki Healing?',
                'excerpt' => 'Introduction to Reiki energy healing and self-healing concepts.',
                'description' => 'Reiki is a Japanese energy healing technique that channels universal life force energy through the hands to promote physical, emotional, and spiritual healing. The word "Reiki" means "universal life force energy." Reiki practitioners serve as conduits for this healing energy, which flows to areas where it is most needed. The practice is based on the belief that illness and stress result from blockages in the body\'s energy flow. By removing these blockages, Reiki restores balance and promotes natural healing. Reiki is non-invasive, deeply relaxing, and complementary to other healing modalities. It can be performed in person or remotely. Regular Reiki sessions reduce stress, promote relaxation, enhance immune function, and support emotional healing. Reiki also empowers individuals to perform self-healing, promoting personal responsibility for wellbeing.',
                'image' => null,
                'slug' => 'what-is-reiki-healing',
                'is_active' => true,
            ],
            [
                'title' => 'Why Wellness and Holistic Living Matter',
                'excerpt' => 'Explains holistic health, conscious wellness, and spiritual well-being.',
                'description' => 'In today\'s fast-paced world, holistic wellness has become essential for maintaining physical, mental, and spiritual health. Holistic living approaches health as a unified system where body, mind, spirit, and environment are interconnected. True wellness goes beyond the absence of disease; it encompasses vitality, purpose, and spiritual fulfillment. Conscious wellness involves making intentional choices about nutrition, movement, sleep, relationships, and spiritual practices. It requires mindfulness about how we live, what we consume, and how we relate to ourselves and others. Holistic living recognizes that healing one aspect of life supports healing in all areas. By cultivating practices like yoga, meditation, Ayurveda, and energy work, we create a foundation for sustainable wellness. Holistic health empowers individuals to take responsibility for their wellbeing and live more authentic, purposeful lives.',
                'image' => null,
                'slug' => 'why-wellness-and-holistic-living-matter',
                'is_active' => true,
            ],
            [
                'title' => 'Devika Gurung\'s Self-Healing Journey',
                'excerpt' => 'Founder story about healing, yoga, kundalini awakening, and spiritual growth.',
                'description' => 'Devika Gurung\'s journey to founding Himalayan Yogini began with personal transformation through yoga and self-healing practices. After overcoming severe health challenges that conventional medicine could not resolve, Devika turned to yoga, meditation, and spiritual practices. Through consistent practice and deep inner work, she experienced profound healing at physical, emotional, and spiritual levels. Her personal transformation sparked a mission to share these healing methods with others. Inspired by Australian yoga teachers in her youth, she studied Hatha Yoga and Ayurvedic massage in Kathmandu, then founded the Nepali Yoga Center in 2000, which evolved into Himalayan Yogini. Her journey demonstrates the transformative power of yoga and self-healing. Devika\'s dedication to kundalini awakening, chakra work, energy healing, and spiritual development created a sanctuary where others can experience similar healing and transformation. Her story inspires students to embrace their own healing journey.',
                'image' => null,
                'slug' => 'devika-gurung-self-healing-journey',
                'is_active' => true,
            ],
            [
                'title' => 'Buddhism and Yoga at Himalayan Yogini',
                'excerpt' => 'Content about Buddhist teachings, meditation, monastery life, and healing.',
                'description' => 'Himalayan Yogini integrates Buddhist teachings and practices with yoga and Ayurveda, creating a comprehensive spiritual path. Buddhism and yoga share common roots in ancient Eastern wisdom and complement each other beautifully. Buddhist meditation practices deepen the meditative aspects of yoga, while yoga asanas and pranayama support Buddhist practice. Many team members at Himalayan Yogini have studied in Buddhist monasteries, bringing authentic Buddhist philosophy and teachings to programs. The monastery life emphasizes discipline, mindfulness, and service—values that enhance spiritual development. Buddhist teachings on compassion, mindfulness, and the nature of mind align with yoga philosophy. The integration of Buddhism and yoga creates powerful healing experiences where students learn to transform suffering, cultivate inner peace, and develop compassion for all beings. This unique blend makes Himalayan Yogini a space where ancient wisdom traditions converge to facilitate profound spiritual growth.',
                'image' => null,
                'slug' => 'buddhism-and-yoga-at-himalayan-yogini',
                'is_active' => true,
            ],
            [
                'title' => 'Benefits of Ayurvedic Deep Tissue Massage',
                'excerpt' => 'Explains Ayurvedic massage techniques, dosha balancing, and relaxation.',
                'description' => 'Ayurvedic Deep Tissue Massage combines therapeutic techniques with Ayurvedic principles and herbal oils to promote deep healing and relaxation. The massage works with specific pressure points and energy channels (marmas and nadis) to release tension, improve circulation, and balance the doshas. The use of warm, medicated herbal oils nourishes the skin, calms the nervous system, and facilitates the release of stored tension and toxins. Ayurvedic massage recognizes that each person\'s constitution is unique, so oils and techniques are customized to individual needs. Benefits include relief from muscle tension and pain, improved circulation and lymphatic function, enhanced flexibility, emotional release, deep relaxation, and restoration of energetic balance. Regular Ayurvedic massage therapy supports the body\'s natural healing processes, prevents disease, and promotes overall wellbeing. The combination of skilled touch and healing oils makes Ayurvedic massage a powerful therapeutic modality.',
                'image' => null,
                'slug' => 'benefits-of-ayurvedic-deep-tissue-massage',
                'is_active' => true,
            ],
        ];

        foreach ($blogs as $blog) {
            Blogs::create($blog);
        }
    }
}
