<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    /**
     * @return View
     */
    public function documents()
    {
        $documents = Document::where([
            ['status', Document::ACTIVE],
            ['user_id', auth()->id()],
        ])->whereHas('versions')->latest()->paginate(20);
        if (auth()->user()->role == User::CLIENT) {
            $documents = Document::where([
                ['status', Document::ACTIVE]
            ])
                ->whereHas('versions')
                ->whereHas('clients', function ($query) {
                    $query->where('user_id', auth()->id());
                })
                ->latest()->paginate(20);
        }
        return view('dashboard', compact('documents'));
    }

    /**
     * @param Document $document
     * @return View
     */
    public function show(Document $document)
    {
        $document->load('versions');
        $latestVersion     = $document->versions()->orderBy('version', 'DESC')->first();

        if (auth()->user()->role == User::CLIENT) {
            $lastViewedVersion = DocumentVersion::where('document_id', $document->id)
                ->where('version', auth()->user()->lastViewedVersion($document)->pivot->last_viewed_version)
                ->first();
            return view('client_document_diff', compact('document', 'latestVersion', 'lastViewedVersion'));
        }
        return view('document_show', compact('document', 'latestVersion'));
    }
}
