<?php

namespace App\Services;

use App\Models\DetalhesFormaPag;
use Carbon\Carbon;

class AtualizarStatusParcelas
{
    public function executar()
    {
        $parcelas = DetalhesFormaPag::whereNotIn('det_situacao', [
            'Pago',
            'Quitado',
            'Inadimplencia'
        ])->get();

        foreach ($parcelas as $parcela) {
            $diasAtraso = Carbon::parse($parcela->det_forma_data_venc)
                ->diffInDays(now(), false);

            if ($diasAtraso > 3) {
                if ($parcela->det_situacao === 'Acordo') {
                    $parcela->update([
                        'det_situacao' => 'Inadimplencia'
                    ]);
                } elseif ($parcela->det_situacao === 'Não Pago') {
                    $parcela->update([
                        'det_situacao' => 'Atrasado'
                    ]);
                }
            }
        }
    }
}
