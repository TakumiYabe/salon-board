<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendances;
use App\Models\Deductions;
use App\Models\Provisions;
use App\Models\Staffs;
use App\Models\StaffTypes;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class StaffsController extends Controller
{
    /**
     * 一覧
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function index()
    {
        $staffs = Staffs::with('staff_types')->get();

        return view('staffs/index', compact('staffs'));
    }

    /**
     * 更新
     * @param Request $request
     * @param $id
     * @return Application|Factory|View|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $id = null)
    {
        // POST時
        if ($request->isMethod('post')) {
            $validationRules = [
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

            $validator = Validator::make($request->all(), $validationRules);
            if ($validator->fails()) {
                session()->flash('flash_message.fail', __($validator->errors()->first()));
                return redirect()->back();
            }
            $validatedData = $request->input();

            // 編集
            if ($request->id) {
                $staff = Staffs::where('id', $request->id)->first();
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
     * @return Application|Factory|View|\Illuminate\Foundation\Application
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

    /**
     * スタッフ取得(非同期)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request)
    {
        $staffId = $request->input('staff_id');
        $staff = Staffs::with('staff_types')
            ->where(['id' => $staffId])
            ->first();

        return response()->json($staff);
    }

    /**
     * 給与明細取得(非同期)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPayroll(Request $request)
    {
        $staffId = $request->input('staff_id');
        $yearMonth = $request->input('year_and_month');

        $staff = Staffs::find($staffId);
        $payRoll = $staff->getPayroll($yearMonth);

        return response()->json($payRoll);
    }

    /**
     * 勤怠表示
     * @param $id
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
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
     * 勤怠取得(非同期)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAttendances(Request $request)
    {
        $staffId = $request->input('staff_id');
        $yearMonth = $request->input('year_and_month');

        $staff = Staffs::find($staffId);
        $attendances = $staff->getAttendances($yearMonth);

        return response()->json($attendances);
    }

    /**
     * 支給・控除表示
     * @param $id
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function displayProvisionAndDeduction($id)
    {
        $staff = Staffs::find($id);

        $yearMonthList = Provisions::where('staff_id', $staff->id)
            ->orderBy('year_and_month', 'desc')
            ->pluck('year_and_month')
            ->toArray();

        // ※先月の支給・控除を未作成の場合があるのでその場合は先月をリストに追加
        $lastMonth = Carbon::now()->subMonth()->year . '-' . str_pad(Carbon::now()->subMonth()->month, 2, '0', STR_PAD_LEFT);

        if (!in_array($lastMonth, $yearMonthList)) {
            array_unshift($yearMonthList, $lastMonth);
        }

        return view('staffs.display-provision-and-deduction',)
            ->with(compact('staff', 'yearMonthList'));
    }

    /**
     * 支給・控除取得(非同期)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProvisionAndDeduction(Request $request)
    {
        $staffId = $request->input('staff_id');
        $yearMonth = $request->input('year_and_month');

        $staff = Staffs::find($staffId);

        return response()->json([
            'provision' => $staff->getProvision($yearMonth),
            'deduction' => $staff->getDeduction($yearMonth),
        ]);
    }

    /**
     * @param $staffId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function editProvisionAndDeduction($staffId, Request $request)
    {
        if (!$request->isMethod('post')) {
            throw new BadRequestException();
        }

        // バリデーションチェック
        $validationRules = [
            'year_and_month' => 'required',
            'work_salary' => 'numeric',
            'over_work_salary' => 'numeric',
            'bonus' => 'numeric',
            'commuting_allowance' => 'numeric',
            'health_insurance_fee' => 'numeric',
            'employee_person_insurance_fee' => 'numeric',
            'employee_insurance_fee' => 'numeric',
            'income_tax' => 'numeric',
            'resident_tax' => 'numeric',
        ];

        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            session()->flash('flash_message.fail', __($validator->errors()->first()));
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $requestData = $request->input();
            // 支給の保存
            $provision = Provisions::where('staff_id', $staffId)
                ->where('year_and_month', $requestData['year_and_month'])
                ->get()
                ->first() ?? null;

            if ($provision) {
                $provision->update([
                    'work_salary' => $requestData['work_salary'] ?? 0,
                    'over_work_salary' => $requestData['over_work_salary'] ?? 0,
                    'bonus' => $requestData['bonus'] ?? 0,
                    'commuting_allowance' => $requestData['commuting_allowance'] ?? 0,
                    'taxable_amount' => $requestData['work_salary'] ?? 0 + $requestData['over_work_salary'] ?? 0 + $requestData['bonus'] ?? 0,
                    'tax_exempt_amount' => $requestData['commuting_allowance'] ?? 0,
                ]);
            } else {
                $provision = new Provisions([
                    'staff_id' => $staffId,
                    'year_and_month' => $requestData['year_and_month'] ?? 0,
                    'work_salary' => $requestData['work_salary'] ?? 0,
                    'over_work_salary' => $requestData['over_work_salary'] ?? 0,
                    'bonus' => $requestData['bonus'] ?? 0,
                    'commuting_allowance' => $requestData['commuting_allowance'] ?? 0,
                    'taxable_amount' => $requestData['work_salary'] ?? 0 + $requestData['over_work_salary'] ?? 0 + $requestData['bonus'] ?? 0,
                    'tax_exempt_amount' => $requestData['commuting_allowance'] ?? 0,
                ]);

                $provision->save();
            }

            //　控除の保存
            $deduction = Deductions::where('staff_id', $staffId)
                ->where('year_and_month', $requestData['year_and_month'])
                ->get()
                ->first() ?? null;

            if ($deduction) {
                $deduction->update([
                    'health_insurance_fee' => $requestData['health_insurance_fee'] ?? 0,
                    'employee_person_insurance_fee' => $requestData['employee_person_insurance_fee'] ?? 0,
                    'employee_insurance_fee' => $requestData['employee_insurance_fee'] ?? 0,
                    'income_tax' => $requestData['income_tax'] ?? 0,
                    'resident_tax' => $requestData['resident_tax'] ?? 0,
                    'social_security_amount' => $requestData['health_insurance_fee'] ?? 0 + $requestData['employee_person_insurance_fee'] ?? 0 + $requestData['employee_insurance_fee'] ?? 0,
                    'tax_amount' => $requestData['income_tax'] ?? 0 + $requestData['resident_tax'] ?? 0,
                ]);

                $successMessage = __('更新に成功しました。');
            } else {
                $deduction = new Deductions([
                    'staff_id' => $staffId,
                    'year_and_month' => $requestData['year_and_month'] ?? 0,
                    'health_insurance_fee' => $requestData['health_insurance_fee'] ?? 0,
                    'employee_person_insurance_fee' => $requestData['employee_person_insurance_fee'] ?? 0,
                    'employee_insurance_fee' => $requestData['employee_insurance_fee'] ?? 0,
                    'income_tax' => $requestData['income_tax'] ?? 0,
                    'resident_tax' => $requestData['resident_tax'] ?? 0,
                    'social_security_amount' => $requestData['health_insurance_fee'] ?? 0 + $requestData['employee_person_insurance_fee'] ?? 0 + $requestData['employee_insurance_fee'] ?? 0,
                    'tax_amount' => ($requestData['income_tax'] + $requestData['resident_tax']) ?? 0,
                ]);
                $deduction->save();

                $successMessage = __('登録に成功しました。');
            }

            DB::commit();

            session()->flash('flash_message.success', $successMessage);
            return redirect()->route('staffs.index');
        } catch (\Exception $e) {
            session()->flash('flash_message.fail', __('登録・更新に失敗しました。'));
            return redirect()->back()
                ->with('id', $staffId);
        }
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
