<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Quotation extends Model
{
    //
    protected $table = 'quotation';

    public function getRate(){
        return $this->hasMany(QuotationItem::class, 'quotation', 'id');
    }

    public function getSumQuotationIdr(){
        return $this->hasOne(QuotationItem::class, 'quotation', 'id')->where('currency', 43)->select("quotation", DB::raw("sum(rate * qty) as total"))->groupBy('quotation');
    }

    public function getSumQuotationUsd(){
        return $this->hasOne(QuotationItem::class, 'quotation', 'id')->where('currency', 44)->select("quotation", DB::raw("sum(rate * qty) as total"))->groupBy('quotation');
    }

    public function getSi(){
        return $this->hasOne(ShippingInstruction::class, 'quotation', 'id');
    }
}
