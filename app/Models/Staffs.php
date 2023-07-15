<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staffs extends Model
{
    use HasFactory;

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
                $model->_setCode();
                // TODO とりあえずid=1
                $model->insert_staff_id = 1;
                $model->update_staff_id = 1;
            }
        });
    }

    public function staff_types()
    {
        return $this->belongsTo(StaffTypes::class, 'staff_type_id', 'id');
    }

    public function createMergeData($data) {
        return [
            'id' => $data['id'] ?? null,
            'name_kana' => $data['name_kana'],
            'name' => $data['name'],
            'birthday' => new DateTime($data['birthday_year'] . '-' . $data['birthday_month'] . '-' . $data['birthday_day']),
            'sex_code' => $data['sex_code'],
            'address' => $data['address'] || '',
            'tel' => $data['tel'] || '',
            'mail_address' => $data['mail_address'] || '',
            'staff_type_id' => $data['staff_type_id'],
            'hourly_wage' => $data['hourly_wage'],
            'haire_date' => new DateTime($data['haire_date_year'] . '-' . $data['haire_date_month'] . '-' . $data['haire_date_day']),
            'memo' => $data['memo'] || '',
        ];

    }

    private function _setCode() {
        $staff = Staffs::with('staff_types')->get()->last();
        $code = str_pad($staff->id + 1, 4, '0', STR_PAD_LEFT);

        $this->code = $code;
    }

}
