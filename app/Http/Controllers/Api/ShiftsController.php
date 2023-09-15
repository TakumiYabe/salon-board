<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Provisions;
use App\Models\ShiftDetails;
use App\Models\Shifts;
use App\Models\ShiftSubmissionDetails;
use App\Models\ShiftSubmissions;
use App\Models\ShiftTypes;
use App\Models\Staffs;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShiftsController extends Controller
{
    /**
     * 表示
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function display()
    {
        $yearMonthList = Shifts::orderBy('year_and_month', 'desc')
            ->pluck('year_and_month')
            ->toArray();

        $nextMonth = Carbon::now()->addMonth()->year . '-' . str_pad(Carbon::now()->addMonth()->month, 2, '0', STR_PAD_LEFT);
        if (!in_array($nextMonth, $yearMonthList)) {
            array_unshift($yearMonthList, $nextMonth);
        }

        return view('shifts.display',)
            ->with(compact('yearMonthList'));
    }

    /**
     * 取得
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request)
    {
        $yearMonth = $request->input('year_and_month');

        $shift = Shifts::with('shift_details')
            ->where(['year_and_month' => $yearMonth])
            ->first();

        // ※配列が空の時新規で作成する
        if (!$shift) {
            $shift = new Shifts([
                'year_and_month' => $yearMonth,
            ]);

            $parts = explode('-', $yearMonth);
            $year = $parts[0];
            $month = $parts[1];
            $lastDay = Carbon::createFromDate($year, $month)->endOfMonth()->day;
            $staffs = Staffs::get();
            for ($i = 0; $i < $lastDay; $i++) {
                foreach ($staffs as $staff) {
                    $shiftDetail = new ShiftDetails([
                        'staff_id' => $staff->id,
                        'date' => $yearMonth . '-' . sprintf("%02d", $i + 1),
                    ]);

                    $shiftDetails[] = $shiftDetail;
                }
            }
            $shift->shift_details = $shiftDetails;
        }

        $shift->details = collect($shift->shift_details)
            ->groupBy('formatted_date');

        $shiftSubmissions = collect(ShiftSubmissions::with('shift_submission_details')
            ->where(['year_and_month' => $yearMonth])
            ->get())
            ->mapWithKeys(function ($row) {
                $row->details = collect($row->shift_submission_details)
                    ->mapWithKeys(function ($detail) {
                        return [$detail['date'] => $detail];
                    });

                return [$row['staff_id'] => $row];
            })
            ->toArray();

        return response()->json([
            'staffs' => Staffs::get()
                ->mapWithKeys(function ($staff) {
                    return [$staff['id'] => $staff];
                })
                ->toArray(),
            'shift' => $shift,
            'shift_submissions' => $shiftSubmissions,
            'shift_types' => ShiftTypes::all(),
        ]);
    }

    public function edit(Request $request)
    {
        if ($request->isMethod('post')) {
            // 更新・新規データ作成
            $parseRows = [];

            foreach ($request->input() as $key => $value) {
                $parts = explode('_', $key, 3);
                $date = $parts[0];
                $staffId = $parts[1] ?? null;
                $property = $parts[2] ?? null;

                // 更新データ以外のフォームデータの時は処理しない
                if (!is_numeric($staffId)) {
                    continue;
                }

                $parseRows[$date . '_' . $staffId][$property] = $value;
            }

            $id = $request->input('id');
            $yearMonth = $request->input('year_and_month');

            if (!$id) {
                $shift = new Shifts([
                    'year_and_month' => $yearMonth,
                ]);

                $shift->save();

                foreach ($parseRows as $parseRow) {
                    $check = $parseRow['check'] ?? '';
                    $shiftDetail = new ShiftDetails([
                        'staff_id' => $parseRow['staff_id'],
                        'shift_id' => $shift->id,
                        'date' => $parseRow['date'],
                        'work_time_from' => $parseRow['work_time_from'],
                        'work_time_to' => $parseRow['work_time_to'],
                    ]);
                    if ($check === 'x') {
                        $shiftDetail['is_work_off'] = 1;
                    } else if (is_numeric($check)) {
                        $shiftDetail['shift_type_id'] = $parseRow['check'];
                    }
                    $shiftDetail->save();
                }
            } else {
                foreach ($parseRows as $parseRow) {
                    $check = $parseRow['check'] ?? '';
                    $shiftDetail = ShiftDetails::find($parseRow['id']);
                    $shiftDetail['date'] = $parseRow['date'];
                    $shiftDetail['work_time_from'] = $parseRow['work_time_from'];
                    $shiftDetail['work_time_to'] = $parseRow['work_time_to'];

                    if ($check === 'x') {
                        $shiftDetail['is_work_off'] = 1;
                    } else if (is_numeric($check)) {
                        $shiftDetail['is_work_off'] = 0;
                        $shiftDetail['shift_type_id'] = $parseRow['check'];
                    }
                    $shiftDetail->save();
                }
            }
        }
        return redirect()->route('shifts.display');
    }
}
