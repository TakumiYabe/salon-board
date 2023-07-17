<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
                'birthday_year' => 'required|numeric|max:3000',
                'birthday_month' => 'required|numeric|max:12',
                'birthday_day' => 'required|numeric|max:31',
                'sex_code' => 'required',
                'address' => 'max:100',
                'tel' => 'max:20',
                'mail_address' => 'email|max:100',
                'staff_type_id' => 'required',
                'hourly_wage' => 'required|numeric',
                'haire_date_year' => 'required|numeric|max:3000',
                'haire_date_month' => 'required|numeric|max:12',
                'haire_date_day' => 'required||max:31',
                'memo' => 'max:400',
            ];

            // 編集
            if ($request->id) {
                $staff = Staffs::where('id', $request->id);
                $validatedData = $request->validate($validationRules);
                $mergeData = (new Staffs)->createMergeData($validatedData);

                if ($staff->update($mergeData)) {
                    return view('staffs/index')
                        ->with('staffs', Staffs::with('staff_types')->get())
                        ->with('flash_message', __('編集に成功しました。'));
                } else {
                    session(['inputData' => $request->all()]);
                    return redirect()->back()
                        ->with('staffs', Staffs::with('staff_types')->get())
                        ->with('flash_message', __('編集に失敗しました。'));
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
                    return view('staffs/index')
                        ->with('staffs', Staffs::with('staff_types')->get())
                        ->with('flash_message', __('新規登録に成功しました。'));
                } else {
                    return view('staffs/index')
                        ->with('staffs', Staffs::with('staff_types')->get())
                        ->with('flash_message', __('新規登録に失敗しました。'));
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

        return view('staffs/edit',)
            ->with(compact('staff', 'staffTypes', 'sexes'));
    }

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
