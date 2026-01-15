<?php

namespace App\Repositories;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class CreditReportRepository
{
    public function getChunkData(array $reportIds): Collection
    {
        $loans = DB::table('subscription_reports')
            ->join('subscriptions', 'subscriptions.id', '=', 'subscription_reports.subscription_id')
            ->join('report_loans', 'subscription_reports.id', '=', 'report_loans.subscription_report_id')
            ->whereIn('subscription_reports.id', $reportIds)
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

        $cards = DB::table('subscription_reports')
            ->join('subscriptions', 'subscriptions.id', '=', 'subscription_reports.subscription_id')
            ->join('report_credit_cards', 'subscription_reports.id', '=', 'report_credit_cards.subscription_report_id')
            ->whereIn('subscription_reports.id', $reportIds)
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

        $others = DB::table('subscription_reports')
            ->join('subscriptions', 'subscriptions.id', '=', 'subscription_reports.subscription_id')
            ->join('report_other_debts', 'subscription_reports.id', '=', 'report_other_debts.subscription_report_id')
            ->whereIn('subscription_reports.id', $reportIds)
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

        return $loans
            ->unionAll($cards)
            ->unionAll($others)
            ->orderBy('report_id')
            ->get();
    }

    public function getBaseQuery(?string $startDate, ?string $endDate): Builder
    {
        $query = DB::table('subscription_reports')->select('id');

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query;
    }
}
