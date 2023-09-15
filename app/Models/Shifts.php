<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Shifts extends Model
{
    const CREATED_AT = 'inserted';
    const UPDATED_AT = 'updated';

    protected $guarded = [
        'id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!$model->exists) {
                // TODO とりあえずid=1
                $model->insert_staff_id = 1;
                $model->update_staff_id = 1;
            }
        });
    }

    public function shift_details()
    {
        return $this->hasMany(ShiftDetails::class, 'shift_id', 'id');
    }
}
