<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestCertificateContractStatus extends Model
{
    protected $table = 'request_sertificate_contract_status';
    protected $guarded = 'id';

    public function RequestCertificateContract()
    {
        return $this->belongsTo(RequestCertificateContract::class, 'request_sertificate_contract', 'id');
    }
}
