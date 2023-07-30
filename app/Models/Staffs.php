<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function createMergeData($data)
    {
        return [
            'id' => $data['id'] ?? null,
            'name_kana' => $data['name_kana'],
            'name' => $data['name'],
            'birthday' => new DateTime($data['birthday']),
            'sex_code' => $data['sex_code'],
            'address' => $data['address'] ?? '',
            'tel' => $data['tel'] ?? '',
            'mail_address' => $data['mail_address'] ?? '',
            'staff_type_id' => $data['staff_type_id'],
            'hourly_wage' => $data['hourly_wage'],
            'haire_date' => new DateTime($data['haire_date']),
            'memo' => $data['memo'] ?? '',
        ];
    }

    /**
     * スタッフをvoidにします。
     * @return void
     */
    public function void()
    {
        $this->is_void = 1;
        $this->save();
    }

    /**
     * スタッフのvoidを解除します。
     * @return void
     */
    public function unVoid()
    {
        $this->is_void = 0;
        $this->save();
    }

    /**
     * 給与明細を取得します。
     * @param $yearMonth
     * @return array
     * @throws \Exception
     */
    public function getPayroll($yearMonth)
    {
        return [
            // 当年差引支給額
            'year_total_provision' => $this->_getYearTotalProvision($yearMonth),
            // 勤怠
            'attendances' => Attendances::where('staff_id', $this->id)
                ->where('date', 'LIKE', $yearMonth . '%')
                ->select([
                    DB::raw('COUNT(*) as work_days'),
                    DB::raw('SUM(TIME_TO_SEC(work_time)) as total_work_time'),
                    DB::raw('SUM(TIME_TO_SEC(over_work_time)) as total_over_work_time'),
                ])
                ->groupBy('staff_id')
                ->get()
                ->first(),
            // 支給
            'provision' => Provisions::where('staff_id', $this->id)
                ->where('year_and_month', $yearMonth)
                ->select([
                    'work_salary',
                    'over_work_salary',
                    'bonus',
                    'commuting_allowance',
                    'taxable_amount',
                    'tax_exempt_amount',
                ])
                ->selectRaw('taxable_amount + tax_exempt_amount as total_amount')
                ->get()
                ->first(),
            'deduction' => Deductions::where('staff_id', $this->id)
                ->where('year_and_month', $yearMonth)
                ->select([
                    'health_insurance_fee',
                    'employee_person_insurance_fee',
                    'employee_insurance_fee',
                    'income_tax',
                    'resident_tax',
                    'social_security_amount',
                    'tax_amount',
                ])
                ->selectRaw('social_security_amount + tax_amount as total_amount')
                ->get()
                ->first(),
        ];
    }

    private function _getYearTotalProvision($yearMonth)
    {
        $date = new DateTime($yearMonth);
        $year = $date->format('Y');

        // 年間の給与の集計
        $groupingProvisions = Provisions::where('staff_id', $this->id)
            ->where('year_and_month', 'LIKE', $year . '%')
            ->selectRaw('SUM(taxable_amount) + SUM(tax_exempt_amount) as total_amount')
            ->first();

        // 年間の控除の集計
        $groupingDeductions = Deductions::where('staff_id', $this->id)
            ->where('year_and_month', 'LIKE', $year . '%')
            ->selectRaw('SUM(social_security_amount) + SUM(tax_amount) as total_amount')
            ->first();

        return $groupingProvisions->total_amount - $groupingDeductions->total_amount;
    }

    public function getAttendances($yearMonth)
    {
        $totalAttendanceInformation = Attendances::where('staff_id', $this->id)
                ->where('date', 'LIKE', $yearMonth . '%')
            ->select([
                DB::raw('SUM(TIME_TO_SEC(work_time)) as total_work_time'),
                DB::raw('SUM(TIME_TO_SEC(over_work_time)) as total_over_work_time'),
            ])
            ->groupBy('staff_id')
            ->get()
            ->first();

        return [
            'attendance_details' => Attendances::where('staff_id', $this->id)
                ->where('date', 'LIKE', $yearMonth . '%')
                ->get(),
            'total' => [
                'total_work_time' => $totalAttendanceInformation->total_work_time,
                'total_over_work_time' => $totalAttendanceInformation->total_over_work_time,
                'work_salary' => $this->hourly_wage/60*$totalAttendanceInformation->total_work_time/60,
                'over_work_salary' => $this->hourly_wage/60*$totalAttendanceInformation->total_over_work_time/60,
            ],
        ];
    }


    private function _setCode()
    {
        $staff = Staffs::with('staff_types')->get()->last();
        $code = str_pad($staff->id + 1, 4, '0', STR_PAD_LEFT);

        $this->code = $code;
    }

}
