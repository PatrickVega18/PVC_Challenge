<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;
use Illuminate\Database\Query\Builder;

class CreditReportService
{
    public function exportStream(?string $startDate, ?string $endDate): void
    {
        $fileName = 'credit_report_' . date('Y-m-d_H-i-s') . '.xlsx';

        $writer = new Writer();
        $writer->openToBrowser($fileName);

        $header = [
            'ID', 'Nombre Completo', 'DNI', 'Email', 'Teléfono',
            'Compañía', 'Tipo de deuda', 'Situación', 'Atraso',
            'Entidad', 'Monto total', 'Línea total', 'Línea usada',
            'Reporte subido el', 'Estado'
        ];

        $writer->addRow(Row::fromValues($header));

        // Helper to apply filters cleanly to each sub-query
        $applyFilters = function (Builder $query) use ($startDate, $endDate) {
            if ($startDate) {
                $query->whereDate('subscription_reports.created_at', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('subscription_reports.created_at', '<=', $endDate);
            }
        };

        // 1. Loans Query
        $loansQuery = DB::table('subscriptions')
            ->join('subscription_reports', 'subscriptions.id', '=', 'subscription_reports.subscription_id')
            ->join('report_loans', 'subscription_reports.id', '=', 'report_loans.subscription_report_id')
            ->select([
                'subscription_reports.id as report_id',
                'subscriptions.full_name',
                'subscriptions.document',
                'subscriptions.email',
                'subscriptions.phone',
                'report_loans.bank as company',
                DB::raw("'Préstamo' as debt_type"),
                'report_loans.status as situation',
                'report_loans.expiration_days as delay',
                DB::raw("'' as entity"),
                'report_loans.amount as total_amount',
                DB::raw("'0' as total_line"),
                DB::raw("'0' as used_line"),
                'subscription_reports.created_at as report_date',
                DB::raw("'Activo' as status")
            ]);

        $applyFilters($loansQuery);

        // 2. Credit Cards Query
        $cardsQuery = DB::table('subscriptions')
            ->join('subscription_reports', 'subscriptions.id', '=', 'subscription_reports.subscription_id')
            ->join('report_credit_cards', 'subscription_reports.id', '=', 'report_credit_cards.subscription_report_id')
            ->select([
                'subscription_reports.id as report_id',
                'subscriptions.full_name',
                'subscriptions.document',
                'subscriptions.email',
                'subscriptions.phone',
                'report_credit_cards.bank as company',
                DB::raw("'Tarjeta de crédito' as debt_type"),
                DB::raw("'' as situation"),
                DB::raw("'0' as delay"),
                DB::raw("'' as entity"),
                DB::raw("'0' as total_amount"),
                'report_credit_cards.line as total_line',
                'report_credit_cards.used as used_line',
                'subscription_reports.created_at as report_date',
                DB::raw("'Activo' as status")
            ]);

        $applyFilters($cardsQuery);

        // 3. Other Debts Query
        $othersQuery = DB::table('subscriptions')
            ->join('subscription_reports', 'subscriptions.id', '=', 'subscription_reports.subscription_id')
            ->join('report_other_debts', 'subscription_reports.id', '=', 'report_other_debts.subscription_report_id')
            ->select([
                'subscription_reports.id as report_id',
                'subscriptions.full_name',
                'subscriptions.document',
                'subscriptions.email',
                'subscriptions.phone',
                DB::raw("'' as company"),
                DB::raw("'Otra deuda' as debt_type"),
                DB::raw("'' as situation"),
                'report_other_debts.expiration_days as delay',
                'report_other_debts.entity as entity',
                'report_other_debts.amount as total_amount',
                DB::raw("'0' as total_line"),
                DB::raw("'0' as used_line"),
                'subscription_reports.created_at as report_date',
                DB::raw("'Activo' as status")
            ]);

        $applyFilters($othersQuery);

        // Union ALL
        $finalQuery = $loansQuery
            ->unionAll($cardsQuery)
            ->unionAll($othersQuery)
            ->orderBy('report_id');

        foreach ($finalQuery->cursor() as $record) {
            $row = [
                $record->report_id,
                $record->full_name,
                $record->document,
                $record->email,
                $record->phone,
                $record->company,
                $record->debt_type,
                $record->situation,
                $record->delay,
                $record->entity,
                $record->total_amount,
                $record->total_line,
                $record->used_line,
                $record->report_date,
                $record->status,
            ];

            $writer->addRow(Row::fromValues($row));
        }

        $writer->close();
    }
}
