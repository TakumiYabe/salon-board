@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="content-header">
            <div>
                <h2>支給・控除編集画面</h2>
            </div>
            <div class="breadcrumb">
                <a href="/">ホーム</a>
                <span>></span>
                <a href="{{ route('staffs.index')}}">社員一覧</a>
                <span>></span>
                <span>支給・控除編集画面</span>
            </div>
        </div>
        @include('layouts.flash-message')
        <div class="content-body">
            <div class= "react-display"
                 id="display-provision-and-deduction"
                 data-staff-id= {{$staff->id}}
                data-year-month-list={{json_encode($yearMonthList)}}
                data-url={{route('staffs.edit-provision-and-deduction', ['staff_id' => $staff->id])}}></div>
        </div>
    </div>
@endsection
