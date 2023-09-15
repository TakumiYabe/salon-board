<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftSubmissionDetails extends Model
{
    use HasFactory;

    const CREATED_AT = 'inserted';
    const UPDATED_AT = 'updated';

    protected $appends = ['formatted_date'];

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

    public function shift_submissions()
    {
        return $this->belongsTo(ShiftSubmissions::class, 'shift_submission_id');
    }

    public function getFormattedDateAttribute()
    {
        $dayOfWeekMaps = [
            'Sunday' => '日',
            'Monday' => '月',
            'Tuesday' => '火',
            'Wednesday' => '水',
            'Thursday' => '木',
            'Friday' => '金',
            'Saturday' => '土',
        ];
        $date = new DateTime($this->attributes['date']);
        return $date->format('d') . '(' . $dayOfWeekMaps[$date->format('l')] . ')';
    }
}
