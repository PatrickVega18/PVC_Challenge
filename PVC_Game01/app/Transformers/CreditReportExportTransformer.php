<?php

namespace App\Transformers;

use OpenSpout\Common\Entity\Row;

class CreditReportExportTransformer
{
    public function getHeaders(): Row
    {
        return Row::fromValues([
            'ID', 'Nombre Completo', 'DNI', 'Email', 'Teléfono',
            'Compañía', 'Tipo de deuda', 'Situación', 'Atraso',
            'Entidad', 'Monto total', 'Línea total', 'Línea usada',
            'Reporte subido el', 'Estado'
        ]);
    }

    public function transform(object $record): Row
    {
        return Row::fromValues([
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
        ]);
    }
}
