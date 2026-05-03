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
        Instructor::create([
            'name' => 'John Doe',
            'profession' => 'Yoga Instructor',
            'experience' => 5,
            'bio' => 'John has been teaching yoga for over 5 years...',
            'specialities' => 'Hatha, Vinyasa',
            'certifications' => ['RYT-200', 'RYT-500', 'CPR'],
            'image' => 'instructors/john_doe.jpg',
        ]);

        Instructor::create([
            'name' => 'Jane Smith',
            'profession' => 'Meditation Expert',
            'experience' => 8,
            'bio' => 'Jane specializes in mindfulness and concentration techniques...',
            'specialities' => 'Vipassana, Zen',
            'certifications' => ['MBSR', 'Mindfulness Coach'],
            'image' => 'instructors/jane_smith.jpg',
        ]);

        Instructor::create([
            'name' => 'Swami Ananda',
            'profession' => 'Yoga Master',
            'experience' => 15,
            'bio' => 'Swami Ananda has lived and taught in various ashrams across Nepal...',
            'specialities' => 'Ashtanga, Kundalini',
            'certifications' => ['Yoga Acharya', 'Ayurveda Practitioner'],
            'image' => 'instructors/swami_ananda.jpg',
        ]);

        // Create 20 random instructors
        Instructor::factory()->count(20)->create();
    }
}
