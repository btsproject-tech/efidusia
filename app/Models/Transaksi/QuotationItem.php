<?php

namespace App\Models\Transaksi;

use App\Models\Master\Dictionary;
use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    //
    protected $table = 'quotation_item';

    public function currencys(){
        return $this->hasOne(Dictionary::class, 'id', 'currency');
    }
}
