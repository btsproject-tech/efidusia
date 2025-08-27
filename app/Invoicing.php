<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoicing extends Model
{
    protected $table = 'invoicing';
    protected $guarded = 'id';

    public function InvoicingNotaris()
    {
        return $this->belongsTo(RequestCertificateNotaris::class, 'id', 'no_batch');
    }
}
