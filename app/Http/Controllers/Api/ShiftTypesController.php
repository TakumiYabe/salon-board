<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShiftTypes;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftTypesController extends Controller
{
    /**
     * 更新
     * @param Request $request
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function edit(Request $request) {
        if ($request->isMethod('post')) {
            // 更新・新規データ作成
            $parseData = [];
            foreach ($request->input() as $key => $value) {
                $parts = explode('_', $key, 2);
                $index = $parts[0];
                $property = $parts[1] ?? null;

                // 更新データ以外のフォームデータの時は処理しない
                if (!is_numeric($index)) {
                    continue;
                }

                $parseData[$index][$property] = $value;
            }
            DB::beginTransaction();
            try {
                // 更新
                $updateShiftTypeIds = collect(ShiftTypes::get())->pluck('id');
                foreach ($updateShiftTypeIds as $id) {
                    $shiftType = ShiftTypes::find($id);
                    $shiftType->update($parseData[$id]);
                    unset($parseData[$id]);
                }

                // 新規
                foreach ($parseData as $data) {
                    $shiftType = new ShiftTypes();
                    $shiftType->fill($data);
                    $shiftType->save();
                }

                DB::commit();

                session()->flash('flash_message.success', __('登録・更新に成功しました。'));
            } catch (\Exception $e) {
                session()->flash('flash_message.fail', __('登録・更新に失敗しました。'));
            }
        }

        $shiftTypes = ShiftTypes::get();

        return view('shift_types.edit',)
            ->with(compact('shiftTypes'));
    }
}
