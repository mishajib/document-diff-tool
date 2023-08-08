<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Author user create
        User::create([
            'name'              => 'Author',
            'username'          => 'author',
            'email'             => 'author@app.com',
            'role'              => User::AUTHOR,
            'email_verified_at' => now(),
            'password'          => bcrypt('password'),
        ]);

        // 300 active/inactive users
        User::factory(300)->create();

        $this->call([
            DocumentSeeder::class,
            DocumentVersionSeeder::class,
            DocumentUserSeeder::class,
        ]);

        $this->command->info('Database seeded successfully.');
    }
}
