<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Model;

class QuotationBuyRate extends Model
{
    //
    protected $table = 'quotation_buyrate';

    public function currencys(){
        return $this->hasOne(Dictionary::class, 'id', 'currency');
    }
}
