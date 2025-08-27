<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class CompanyModel extends Model
{
    //
    protected $table = 'company';

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'company_branch', 'company', 'branch');
    }
}
