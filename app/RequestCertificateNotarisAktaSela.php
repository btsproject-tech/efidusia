<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestCertificateNotarisAktaSela extends Model
{
    protected $table = 'request_serificste_notaris_akta_sela';
    protected $guarded = 'id';

    public function RequestCertificateNotaris()
    {
        return $this->belongsTo(RequestCertificateNotaris::class, 'seritifcate_notaris', 'id');
    }
    public function RequestContract()
    {
        return $this->hasMany(RequestCertificateContract::class, 'id', 'request_sertificate_contract');
    }
}
