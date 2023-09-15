<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Provisions;
use App\Models\ShiftSubmissionDetails;
use App\Models\ShiftSubmissions;
use App\Models\ShiftTypes;
use App\Models\Staffs;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ShiftSubmissionsController extends Controller
{
    /**
     * 表示
     * @param $id
     * @return Application|Factory|View
     */
    public function display($id)
    {
        $staff = Staffs::find($id);

        $yearMonthList = ShiftSubmissions::where('staff_id', $staff->id)
            ->orderBy('year_and_month', 'desc')
            ->pluck('year_and_month')
            ->toArray();

        // ※来月のシフトまで編集できる
        $nextMonth = Carbon::now()->addMonth()->year . '-' . str_pad(Carbon::now()->addMonth()->month, 2, '0', STR_PAD_LEFT);
        if (!in_array($nextMonth, $yearMonthList)) {
            array_unshift($yearMonthList, $nextMonth);
        }

        return view('shift_submissions.display',)
            ->with(compact('staff', 'yearMonthList'));
    }

    /**
     * 取得
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request)
    {
        $staffId = $request->input('staff_id');
        $yearMonth = $request->input('year_and_month');

        $shiftSubmission = ShiftSubmissions::with('shift_submission_details')
            ->where(['staff_id' => $staffId, 'year_and_month' => $yearMonth])
            ->first();

        // ※配列が空の時新規で作成する
        if (!$shiftSubmission) {
            $shiftSubmission = new ShiftSubmissions([
                'staff_id' => $staffId,
                'year_and_month' => $yearMonth,
                'memo' => '',
            ]);

            $parts =explode('-', $yearMonth);
            $year = $parts[0];
            $month = $parts[1];
            $lastDay = Carbon::createFromDate($year, $month)->endOfMonth()->day;
            $shiftSubmissionDetails = [];
            for ($i = 0; $i < $lastDay; $i++) {
                $shiftSubmissionDetail = new ShiftSubmissionDetails([
                    'date' => $yearMonth . '-' . sprintf("%02d", $i + 1),
                ]);

                $shiftSubmissionDetails[] = $shiftSubmissionDetail;
            }

            $shiftSubmission->shift_submission_details = $shiftSubmissionDetails;
        }

        return response()->json([
            'shift_submission' => $shiftSubmission,
            'shift_types' => ShiftTypes::all(),
        ]);
    }

    /**
     * 編集
     * @param $staffId
     * @param Request $request
     * @return RedirectResponse
     */
    public function edit($staffId, Request $request)
    {
        if ($request->isMethod('post')) {
            // detailsの更新・新規データ作成
            $parseRows = [];
            foreach ($request->input() as $key => $value) {
                $parts = explode('_', $key, 2);
                $index = $parts[0];
                $property = $parts[1] ?? null;

                // 更新データ以外のフォームデータの時は処理しない
                if (!is_numeric($index)) {
                    continue;
                }

                $parseRows[$index][$property] = $value;
            }

            $id = $request->input('id');
            $yearMonth = $request->input('year_and_month');
            $memo = $request->input('memo') ?? '';
            if (!$id) {
                $shiftSubmission = new ShiftSubmissions([
                    'staff_id' => $staffId,
                    'year_and_month' => $yearMonth,
                    'memo' => $memo,
                ]);

                $shiftSubmission->save();

                foreach ($parseRows as $parseRow) {
                    $date = $yearMonth . '-' . $parseRow['date'];
                    $check = $parseRow['check'] ?? '';
                    $shiftSubmissionDetail = new ShiftSubmissionDetails([
                        'shift_submission_id' => $shiftSubmission->id,
                        'date' => $date,
                        'work_time_from' => $parseRow['work_time_from'],
                        'work_time_to' => $parseRow['work_time_to'],
                    ]);
                    if ($check === 'x') {
                        $shiftSubmissionDetail['is_work_off'] = 1;
                    } else if (is_numeric($check)) {
                        $shiftSubmissionDetail['shift_type_id'] = $parseRow['check'];
                    }
                    $shiftSubmissionDetail->save();
                }
            } else {
                $shiftSubmission = ShiftSubmissions::find($id);
                $shiftSubmission['memo'] = $memo;

                $shiftSubmission->save();

                foreach ($parseRows as $parseRow) {
                    $check = $parseRow['check'] ?? '';
                    $shiftSubmissionDetail = ShiftSubmissionDetails::find($parseRow['id']);
                    $shiftSubmissionDetail['date'] = $yearMonth . '-' . $parseRow['date'];
                    $shiftSubmissionDetail['work_time_from'] = $parseRow['work_time_from'];
                    $shiftSubmissionDetail['work_time_to'] = $parseRow['work_time_to'];

                    if ($check === 'x') {
                        $shiftSubmissionDetail['is_work_off'] = 1;
                    } else if (is_numeric($check)) {
                        $shiftSubmissionDetail['is_work_off'] = 0;
                        $shiftSubmissionDetail['shift_type_id'] = $parseRow['check'];
                    }
                    $shiftSubmissionDetail->save();
                }
            }
        }
        return redirect()->route('shiftSubmissions.display', ['id' => $staffId]);
    }
}
