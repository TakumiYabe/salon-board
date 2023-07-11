<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staffs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StaffsController extends Controller
{
    public function index()
    {
        $staffs = Staffs::with('staff_types')->get();

        return view('staffs/index', compact('staffs'));
    }

    public function edit($id = null)
    {
        if ($id) {
            $staff = Staffs::with('staff_types')->find($id);
        } else {
            $staff = new Staffs();
        }

        $sexes = collect(Sexes::get())->pluck('name', 'code');

        return view('staffs/edit',
            compact('staff'),
            compact('sexes'),
        );
    }


}
