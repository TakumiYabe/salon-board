<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staffs;
use Illuminate\Http\Request;

class StaffsController extends Controller
{
    public function index(Request $request)
    {
        $staffs = Staffs::with('staff_types')->get();

        return response()->json($staffs);
    }
}
