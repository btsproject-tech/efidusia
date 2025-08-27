<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class SertificateMinuta extends Model
{
    protected $table = 'sertificate_minuta';
    protected $guarded = 'id';

    public function RequestContract()
    {
        return $this->belongsTo(RequestCertificateContract::class, 'request_sertificate_contract', 'id');
    }
}
