<?php

namespace App;

use App\Models\Master\Karyawan;
use Illuminate\Database\Eloquent\Model;

class RequestCertificate extends Model
{
    protected $table = 'request_sertificate';
    protected $guarded = 'id';

    public function Creator()
    {
        return $this->belongsTo(User::class, 'creator', 'id');
    }

    public function RequestContract()
    {
        return $this->hasMany(RequestCertificateContract::class, 'request_sertificate', 'id');
    }
    public function RequestContractStatus()
    {
        return $this->hasMany(RequestCertificateContractStatus::class, 'request_sertificate', 'id');
    }
}
