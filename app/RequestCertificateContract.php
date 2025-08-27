<?php

namespace App;

use App\Models\Master\Karyawan;
use Illuminate\Database\Eloquent\Model;

class RequestCertificateContract extends Model
{
    protected $table = 'request_sertificate_contract';
    // protected $guarded = 'id';

    public function UserDelegate()
    {
        return $this->belongsTo(Karyawan::class, 'delegate_to', 'nik');
    }
    public function UserCreator()
    {
        return $this->belongsTo(User::class, 'creator', 'id');
    }
    public function RequestContract()
    {
        return $this->belongsTo(RequestCertificate::class, 'request_sertificate', 'id');
    }
    public function RequestCertificate()
    {
        return $this->belongsTo(RequestCertificate::class, 'request_sertificate', 'id');
    }

    public function RequestContractStatusDailyReport()
    {
        return $this->hasMany(RequestCertificateContractStatus::class, 'request_sertificate_contract', 'id');
    }
    public function RequestCertificateNotaris()
    {
        return $this->belongsTo(RequestCertificateNotaris::class, 'request_sertificate_notaris', 'id');
    }

    public function DataWarkah()
    {
        return $this->belongsTo(SertificateWarkah::class, 'id', 'request_sertificate_contract');
    }

    public function DataMinuta()
    {
        return $this->hasMany(SertificateMinuta::class, 'request_sertificate_contract', 'id');
    }
}
