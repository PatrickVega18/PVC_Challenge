<?php

namespace App\Jobs;

use App\Services\CreditReportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExportCreditReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected ?string $startDate,
        protected ?string $endDate
    ) {}

    public function handle(CreditReportService $service): void
    {
        $fileName = 'credit_report_' . date('Y-m-d_H-i-s') . '.xlsx';
        $filePath = storage_path('app/public/' . $fileName);

        $service->generateFile($this->startDate, $this->endDate, $filePath);
    }
}
