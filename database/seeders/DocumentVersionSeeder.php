<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\DocumentVersion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentVersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documents = Document::all();

        // generate 2500 document versions using 500 documents - using factory
        $documentID = $documents->random()->id;
        DocumentVersion::factory(2500)->create([
            'document_id' => $documentID,
            'version'     => function () use ($documentID) {
                return DocumentVersion::where('document_id', $documentID)->max('version') + 1;
            },
        ]);

        $this->command->info('Document version seeded successfully.');
    }
}
