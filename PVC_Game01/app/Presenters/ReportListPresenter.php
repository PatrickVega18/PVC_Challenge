<?php

namespace App\Presenters;

use Illuminate\Support\Collection;

class ReportListPresenter
{
    public function present(Collection $files): Collection
    {
        return $files->map(fn($file) => (object) [
            'name' => $file->name,
            'size' => $this->formatSize($file->size_bytes),
            'date' => $this->formatDate($file->last_modified_timestamp),
            'download_url' => route('report.download', $file->name),
        ]);
    }

    private function formatSize(int $bytes): string
    {
        return round($bytes / 1024, 2) . ' KB';
    }

    private function formatDate(int $timestamp): string
    {
        return date('Y-m-d H:i:s', $timestamp);
    }
}
