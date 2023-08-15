@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="content-header">
            <div>
                <h2>給与明細画面</h2>
            </div>
            <div class="breadcrumb">
                <a href="/">ホーム</a>
                <span>></span>
                <a href="{{ route('staffs.index')}}">社員一覧</a>
                <span>></span>
                <span>給与明細画面</span>
            </div>
        </div>
        <div class="content-body">
            <div class="react-display" id="display-payroll" data-staff-id= {{$staff->id}} data-year-month-list={{json_encode($yearMonthList)}}></div>
        </div>
    </div>
@endsection
