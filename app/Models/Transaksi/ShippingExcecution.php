<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Model;

class ShippingExcecution extends Model
{
    //
    protected $table = 'shipping_excecution';

    public function getSi(){
        return $this->hasOne(ShippingInstruction::class, 'id', 'shipping_instruction');
    }

    public function getSeGood(){
        return $this->hasMany(ShippingExecutionGood::class, 'shipping_excecution', 'id');
    }

    public function getQuotation(){
        return $this->hasOne(Quotation::class, 'id', 'quotation');
    }
}
