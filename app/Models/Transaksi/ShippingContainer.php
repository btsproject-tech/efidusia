<?php

namespace App\Models\Transaksi;

use App\Models\Master\Dictionary;
use Illuminate\Database\Eloquent\Model;

class ShippingContainer extends Model
{
    //
    protected $table = 'shipping_container';

    public function size(){
        return $this->hasOne(Dictionary::class, 'term_id', 'container_size');
    }
}
