<?php

namespace App;

use App\Models\Master\Karyawan;
use Illuminate\Database\Eloquent\Model;

class RequestCertificateNotaris extends Model
{
    protected $table = 'request_sertificate_notaris';
    protected $guarded = 'id';


    public function UserNotaris()
    {
        return $this->belongsTo(Karyawan::class, 'users', 'nik');
    }

    public function DataRequestCertificate()
    {
        return $this->belongsTo(RequestCertificate::class, 'request_sertificate', 'id');
    }
    public function RequestContract()
    {
        return $this->hasMany(RequestCertificateContract::class, 'request_sertificate_notaris', 'id');
    }
    public function SertificateAktaSela()
    {
        return $this->hasMany(RequestCertificateNotarisAktaSela::class, 'seritifcate_notaris', 'id');
    }

    public function RequestNotarisBacth()
    {
        return $this->belongsTo(Invoicing::class, 'id', 'no_batch');
    }
}
