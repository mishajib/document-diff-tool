<x-app-layout>
    <h1 class="font-bold text-center">
        {{ $document->title }}
    </h1>

    <h2>Latest Version</h2>
    <pre>{{ $latestVersion->body_content }}</pre>

    @if ($lastViewedVersion)
        <h2>Last Viewed Version</h2>
        <pre>{{ $lastViewedVersion->body_content }}</pre>

        <h2>Diff</h2>
        <div id="diffContainer"></div>
    @endif

    @if ($lastViewedVersion)
        <script src="https://cdnjs.cloudflare.com/ajax/libs/diff-match-patch/1.0.5/index.min.js"></script>
        <script>
            const dmp = new diff_match_patch();
            const diff = dmp.diff_main(
                `{!! addslashes($lastViewedVersion->body_content) !!}`,
                `{!! addslashes($latestVersion->body_content) !!}`
            );
            console.log(diff)
            dmp.diff_cleanupSemantic(diff);
            const diffHtml = dmp.diff_prettyHtml(diff);
            console.log(diffHtml)
            document.getElementById('diffContainer').innerHTML = diffHtml;
        </script>
    @endif
</x-app-layout>
