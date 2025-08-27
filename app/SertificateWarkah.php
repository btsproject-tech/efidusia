<?php

namespace App;

use App\Models\Master\Karyawan;
use Illuminate\Database\Eloquent\Model;

class SertificateWarkah extends Model
{
    protected $table = 'sertificate_warkah';
    protected $guarded = 'id';

    public function RequestContract()
    {
        return $this->belongsTo(RequestCertificateContract::class, 'request_sertificate_contract', 'id');
    }

}
