<?php

namespace Database\Seeders;

use App\Models\Inquiry;
use Illuminate\Database\Seeder;

class InquirySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inquiries = [
            [
                'name' => 'Alice Cooper',
                'email' => 'alice@example.com',
                'question' => 'Is the luxury penthouse still available for viewing next week?',
            ],
            [
                'name' => 'Bob Martin',
                'email' => 'bob@example.com',
                'question' => 'What are the lease terms for the modern urban apartment?',
            ],
            [
                'name' => 'Charlie Brown',
                'email' => 'charlie@example.com',
                'question' => 'Does the eco-friendly home have a warranty on the solar panels?',
            ],
        ];

        foreach ($inquiries as $inquiry) {
            Inquiry::firstOrCreate(
                ['email' => $inquiry['email'], 'question' => $inquiry['question']],
                $inquiry
            );
        }
    }
}
