<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\DocumentUser;
use App\Models\DocumentDiff;
use Illuminate\Support\Str;
use Text_Diff;
use Text_Diff_Renderer_inline;

class GenerateDocumentDiffsCommand extends Command
{
    protected $signature = 'generate:document-diffs';
    protected $description = 'Generate and store document diffs for clients';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $users = User::where([
            ['role', User::CLIENT],
            ['status', User::ACTIVE],
        ])->get();
        foreach ($users as $user) {
            $documents = $user->documents()->where('status', Document::ACTIVE)->get();
            foreach ($documents as $document) {
                $lastViewedVersion = DocumentVersion::where('document_id', $document->id)
                    ->where('version', $document->pivot->last_viewed_version)
                    ->first();

                $latestVersion = $document->versions()->orderBy('version', 'DESC')->first();

                if (!$lastViewedVersion || $lastViewedVersion->id == $latestVersion->id) {
                    continue;
                }

                $diff = $this->calculateDiff($lastViewedVersion->body_content, $latestVersion->body_content);

                DocumentUser::create([
                    'document_id'         => $document->id,
                    'user_id'             => $user->id,
                    'last_viewed_version' => $lastViewedVersion->version,
                    'diff'                => $diff,
                ]);
            }
        }

        $this->info('Document diffs generated and stored.');
    }

    private function calculateDiff($oldContent, $newContent)
    {
        $diff     = new Text_Diff(explode("\n", $oldContent), explode("\n", $newContent));
        $renderer = new Text_Diff_Renderer_inline();

        return $renderer->render($diff);
    }
}
