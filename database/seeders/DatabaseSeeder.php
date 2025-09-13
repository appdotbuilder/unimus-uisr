<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use App\Models\Dataset;
use App\Models\DatasetFile;
use App\Models\CurationReview;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'UISR Admin',
            'email' => 'admin@unimus.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        Profile::factory()->lecturer()->create([
            'user_id' => $admin->id,
            'first_name' => 'Admin',
            'last_name' => 'UISR',
            'department' => 'Computer Science',
            'faculty' => 'Faculty of Engineering and Computer Science',
            'student_id' => 'NIP12345678',
        ]);

        // Create curator user
        $curator = User::factory()->create([
            'name' => 'Dr. Maria Kusuma',
            'email' => 'maria.kusuma@unimus.ac.id',
            'password' => Hash::make('password'),
            'role' => 'curator',
        ]);

        Profile::factory()->lecturer()->create([
            'user_id' => $curator->id,
            'first_name' => 'Maria',
            'last_name' => 'Kusuma',
            'department' => 'Data Science',
            'faculty' => 'Faculty of Engineering and Computer Science',
            'student_id' => 'NIP87654321',
            'orcid' => '0000-0002-1825-0097',
            'bio' => 'Lecturer specializing in machine learning and data mining. Research interests include deep learning applications in healthcare and natural language processing.',
        ]);

        // Create contributor users (lecturers and students)
        $lecturers = User::factory(5)->create([
            'role' => 'contributor',
        ]);

        foreach ($lecturers as $lecturer) {
            Profile::factory()->lecturer()->create([
                'user_id' => $lecturer->id,
            ]);
        }

        $students = User::factory(8)->create([
            'role' => 'contributor',
        ]);

        foreach ($students as $student) {
            Profile::factory()->student()->create([
                'user_id' => $student->id,
            ]);
        }

        // Create viewers
        User::factory(3)->create([
            'role' => 'viewer',
        ]);

        // Create datasets with files and reviews
        $allContributors = User::whereIn('role', ['contributor', 'curator', 'admin'])->get();
        
        foreach ($allContributors as $contributor) {
            // Each contributor creates 1-3 datasets
            $datasetCount = fake()->numberBetween(1, 3);
            
            for ($i = 0; $i < $datasetCount; $i++) {
                $dataset = Dataset::factory()->create([
                    'user_id' => $contributor->id,
                ]);

                // Create 1-2 files per dataset
                $fileCount = fake()->numberBetween(1, 2);
                for ($j = 0; $j < $fileCount; $j++) {
                    DatasetFile::factory()->create([
                        'dataset_id' => $dataset->id,
                        'is_primary' => $j === 0, // First file is primary
                    ]);
                }

                // Add reviews for some datasets
                if ($dataset->status !== 'draft' && fake()->boolean(80)) {
                    $reviewStatus = $dataset->status === 'published' ? 'approved' : 
                                   ($dataset->status === 'rejected' ? 'rejected' : 'pending');
                    
                    CurationReview::factory()->create([
                        'dataset_id' => $dataset->id,
                        'reviewer_id' => $curator->id,
                        'status' => $reviewStatus,
                    ]);
                }
            }
        }

        // Create some additional published datasets for browsing
        Dataset::factory(15)->published()->create()->each(function ($dataset) use ($curator) {
            // Create files
            DatasetFile::factory(2)->create([
                'dataset_id' => $dataset->id,
            ]);
            
            // Mark first file as primary
            $dataset->files()->first()->update(['is_primary' => true]);

            // Create approved review
            CurationReview::factory()->approved()->create([
                'dataset_id' => $dataset->id,
                'reviewer_id' => $curator->id,
            ]);
        });

        // Create some international collaboration datasets
        Dataset::factory(5)->international()->published()->create()->each(function ($dataset) use ($curator) {
            DatasetFile::factory()->primary()->create([
                'dataset_id' => $dataset->id,
            ]);

            CurationReview::factory()->approved()->create([
                'dataset_id' => $dataset->id,
                'reviewer_id' => $curator->id,
            ]);
        });

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin user: admin@unimus.ac.id / password');
        $this->command->info('Curator user: maria.kusuma@unimus.ac.id / password');
        $this->command->info('Other users have randomly generated emails and password: "password"');
    }
}
