<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ShiftSubmissions extends Model
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

    public function shift_submission_details()
    {
        return $this->hasMany(ShiftSubmissionDetails::class, 'shift_submission_id', 'id');
    }
}
