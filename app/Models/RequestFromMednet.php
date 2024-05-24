<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestFromMednet extends Model
{
    use HasFactory;

    protected $fillable = [
            'uzsakymas',
            'data',
            'uzsakovas1',
            'uzsakovas2',
            'laboratorija',
            'vardas',
            'pavarde',
            'paciento_id',
            'lytis',
            'gimtadienis',
            'gydytojas',
            'tyrimas1',
            'tyrimas2',
            'apmokejimas',
            'bruksninis_kodas',
            'meginio_laikas',
            'pastabos',
            'adresas',
            'gimtadienis_nenurodyta',
            'gimtadienis_neiskaitoma',
            'gydytojas_nenurodyta',
            'gydytojas_neiskaitoma',
            'adresas_nenurodyta',
            'adresas_neiskaitoma',
            'tlk10am',
            'tlk10am_kodas',
            'tlk10am_nenurodyta',
            'tlk10am_neiskaitoma',
            'klinikos_laborantas',
            'klinikos_laborantas_kodas',
            'klinikos_medbiologas',
            'klinikos_medbiologas_kodas',
            'suvede_uzsakyma',
            'suvede_uzsakyma_kodas',
            'sdave_rezultatus',
            'isdave_rezultatus_kodas',
            'uzakymo_laikas',
            'analizatorius',
            'analizatorius_kodas',
            'analizes_metodas',
            'analizes_metodas_kodas',
            'ivede_laboratorija',
            'tyrimo_bruksninis_kodas',
            'cito_pozymis'
        ];
}
