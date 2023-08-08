<?php

namespace Database\Seeders;

use App\Models\DocumentUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 8400 document users
        DocumentUser::factory(8400)->create();

        $this->command->info('Document user seeded successfully.');
    }
}
