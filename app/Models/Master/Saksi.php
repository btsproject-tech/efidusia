<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Saksi extends Model
{
    protected $table = 'saksi';

    public function CompanySaksi()
    {
        return $this->belongsTo(CompanyModel::class, 'company', 'id');
    }
}
