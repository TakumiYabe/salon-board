<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffTypes extends Model
{
    use HasFactory;

    public function staffs()
    {
        return $this->belongsTo(Staffs::class, 'staff_type_id');
    }
}
