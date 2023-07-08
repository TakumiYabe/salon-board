<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staffs extends Model
{
    use HasFactory;

    public function staff_types()
    {
        return $this->hasone(StaffTypes::class, 'id');
    }
}
