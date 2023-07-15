<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staffs;
use App\Models\StaffTypes;
use Illuminate\Http\Request;

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

                $staff->update($mergeData);
            // 新規
            } else {
                $staff = new Staffs;

                unset($validationRules['id']);
                $validatedData = $request->validate($validationRules);
                $mergeData = $staff->createMergeData($validatedData);

                $staff->fill($mergeData);
                $staff->password =bcrypt(config('app.defaultPassword'));
                $staff->save();
            }
            $staffs = Staffs::with('staff_types')->get();

            return view('staffs/index', compact('staffs'));
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
                ->with(compact('staff'))
                ->with(compact('staffTypes'))
                ->with(compact('sexes'));
    }


}
