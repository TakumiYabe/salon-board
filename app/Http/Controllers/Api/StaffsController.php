<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendances;
use App\Models\Provisions;
use App\Models\Staffs;
use App\Models\StaffTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StaffsController extends Controller
{
    public function index()
    {
        $staffs = Staffs::with('staff_types')->get();

        return view('staffs/index', compact('staffs'));
    }

    public function edit(Request $request, $id = null)
    {
        // POST時
        if ($request->isMethod('post')) {
            $validationRules = [
                'id' => 'required',
                'name_kana' => 'required|string|max:20',
                'name' => 'required|string|max:20',
                'birthday' => 'required',
                'sex_code' => 'required',
                'address' => 'max:100',
                'tel' => 'max:20',
                'mail_address' => 'email|max:100',
                'staff_type_id' => 'required',
                'hourly_wage' => 'required|numeric',
                'haire_date' => 'required',
                'memo' => 'max:400',
            ];

            // 編集
            if ($request->id) {
                $staff = Staffs::where('id', $request->id)->first();
                $validatedData = $request->validate($validationRules);
                $mergeData = (new Staffs)->createMergeData($validatedData);
                if ($staff->update($mergeData)) {
                    session()->flash('flash_message.success', __('編集に成功しました。'));
                    return redirect()->route('staffs.index');
                } else {
                    session()->flash('flash_message.fail', __('編集に失敗しました。'));
                    return redirect()->back()
                        ->with('staffs', Staffs::with('staff_types')->get());
                }
                // 新規
            } else {
                $staff = new Staffs;

                unset($validationRules['id']);
                $validatedData = $request->validate($validationRules);
                $mergeData = $staff->createMergeData($validatedData);

                $staff->fill($mergeData);
                $staff->password = password_hash(config('app.defaultPassword'), PASSWORD_BCRYPT);

                if ($staff->save()) {
                    session()->flash('flash_message.success', __('新規登録に成功しました。'));
                    return redirect()->route('staffs.index');
                } else {
                    session()->flash('flash_message.fail', __('新規登録に失敗しました。'));
                    return redirect()->back()
                        ->with('staffs', Staffs::with('staff_types')->get());
                }
            }
        } else {
            if ($id) {
                $staff = Staffs::with('staff_types')->find($id);
            } else {
                $staff = new Staffs();
            }
        }

        $staffTypes = collect(StaffTypes::get())->pluck('name', 'id');
        $sexes = collect(Sexes::get())->pluck('name', 'code');

        return view('staffs.edit',)
            ->with(compact('staff', 'staffTypes', 'sexes'));
    }

    /**
     * VOID
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function void($id)
    {
        $staff = Staffs::find($id);
        $staff->void();

        return redirect()->route('staffs.index');
    }

    /**
     * UNVOID
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unVoid($id)
    {
        $staff = Staffs::find($id);
        $staff->unVoid();

        return redirect()->route('staffs.index');
    }

    /**
     * 給与明細表示
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function displayPayroll($id)
    {
        $staff = Staffs::find($id);

        $yearMonthList = Provisions::where('staff_id', $staff->id)
            ->orderBy('year_and_month', 'desc')
            ->pluck('year_and_month')
            ->toArray();

        return view('staffs.display-payroll',)
            ->with(compact('staff', 'yearMonthList'));
    }

    public function getStaff(Request $request) {
        $staffId = $request->input('staff_id');
        $staff = Staffs::with('staff_types')
            ->where(['id' => $staffId])
            ->first();

        return response()->json($staff);
    }

    /**
     * 給与明細を取得します。
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPayroll(Request $request) {
        $staffId = $request->input('staff_id');
        $yearMonth = $request->input('year_and_month');

        $staff = Staffs::find($staffId);
        $payRoll = $staff->getPayroll($yearMonth);

        return response()->json($payRoll);
    }

    public function displayAttendances($id)
    {
        $staff = Staffs::find($id);

        $yearMonthList = array_values(array_unique(Attendances::where('staff_id', $staff->id)
            ->orderBy('year_and_month', 'desc')
            ->pluck('year_and_month')
            ->toArray()));

        return view('staffs.display-attendances',)
            ->with(compact('staff', 'yearMonthList'));
    }

    /**
     * 勤怠を取得します。
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAttendances(Request $request) {
        $staffId = $request->input('staff_id');
        $yearMonth = $request->input('year_and_month');

        $staff = Staffs::find($staffId);
        $attendances = $staff->getAttendances($yearMonth);

        return response()->json($attendances);
    }

    /**
     * パスワード更新(非同期)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {
        $staff = Staffs::find($request->input('id'));

        if (!password_verify($request->input('current_password'), $staff->password)) {
            return response()->json(['error' => '現在のパスワードと登録されているパスワードが異なります。']);
        }
        if (!(8 <= mb_strlen($request->input('password')) && mb_strlen($request->input('password')) <= 20)) {
            return response()->json(['error' => '新しいパスワードは8文字以上20文字以下で入力してください。']);
        }
        if ($request->input('password') !== $request->input('password_confirmation')) {
            return response()->json(['error' => '新しいパスワードと新しいパスワード（確認用）が異なります。']);
        }

        $staff->password = password_hash($request->input('password'), PASSWORD_BCRYPT);
        $staff->save();

        return response()->json(['message' => 'パスワードの変更が完了しました。']);
    }


}
