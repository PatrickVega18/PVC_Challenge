<?php

namespace App\Services;

use App\Repositories\CreditReportRepository;
use App\Transformers\CreditReportExportTransformer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use OpenSpout\Writer\XLSX\Writer;

class CreditReportService
{
    public function __construct(
        protected CreditReportRepository $repository,
        protected CreditReportExportTransformer $transformer
    ) {}

    public function getAvailableReports(): Collection
    {
        return collect(Storage::disk('public')->files())
            ->filter(fn($file) => str_ends_with($file, '.xlsx'))
            ->map(fn($file) => (object) [
                'name' => basename($file),
                'size_bytes' => Storage::disk('public')->size($file),
                'last_modified_timestamp' => Storage::disk('public')->lastModified($file)
            ])
            ->sortByDesc('last_modified_timestamp')
            ->values();
    }

    public function getDownloadPath(string $filename): ?string
    {
        if (!Storage::disk('public')->exists($filename)) {
            return null;
        }

        return Storage::disk('public')->path($filename);
    }

    public function generateFile(?string $startDate, ?string $endDate, string $filePath): void
    {
        DB::disableQueryLog();

        $writer = new Writer();
        $writer->openToFile($filePath);

        $writer->addRow($this->transformer->getHeaders());

        $query = $this->repository->getBaseQuery($startDate, $endDate);

        $query->chunkById(1000, function ($chunks) use ($writer) {
            $ids = $chunks->pluck('id')->toArray();

            $data = $this->repository->getChunkData($ids);

            foreach ($data as $record) {
                $row = $this->transformer->transform($record);
                $writer->addRow($row);
            }

            unset($data);
            unset($ids);

        }, 'id', 'id');

        $writer->close();
    }
}
