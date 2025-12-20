<div class="rounded-lg bg-gray-50 dark:bg-gray-800 p-4 font-mono text-sm overflow-auto max-h-96">
    <pre class="whitespace-pre-wrap wrap-break-words">{{ json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
</div>
