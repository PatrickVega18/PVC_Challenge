<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExportReportRequest;
use App\Jobs\ExportCreditReportJob;
use App\Presenters\ReportListPresenter;
use App\Services\CreditReportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CreditReportController extends Controller
{
    public function index(CreditReportService $service, ReportListPresenter $presenter): View
    {
        $rawFiles = $service->getAvailableReports();
        $files = $presenter->present($rawFiles);

        return view('welcome', compact('files'));
    }

    public function export(ExportReportRequest $request): RedirectResponse
    {
        ExportCreditReportJob::dispatch(
            $request->input('start_date'),
            $request->input('end_date')
        );

        return back()->with('status', 'Solicitud recibida. El reporte se está generando en segundo plano.');
    }

    public function download(string $filename, CreditReportService $service): BinaryFileResponse
    {
        $path = $service->getDownloadPath($filename);

        if (!$path) {
            abort(404);
        }

        return response()->download($path);
    }
}
