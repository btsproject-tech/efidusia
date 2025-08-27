<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Model;

class ShippingInstruction extends Model
{
    //
    protected $table = 'shipping_instruction';

    public function getContainer(){
        return $this->hasMany(ShippingContainer::class, 'shipping_instructions', 'id');
    }

}
