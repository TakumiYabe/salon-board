@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="content-title">
            <div>
                <h2>シフト提出画面</h2>
            </div>
            <div class="breadcrumb">
                <a href="/">ホーム</a>
                <span>></span>
                <span>シフト提出画面</span>
            </div>
        </div>
        <div class="content-body">
            <div class="react-display" id="edit-shift-submissions" data-staff-id={{$staff->id}} data-year-month-list={{json_encode($yearMonthList)}} data-config-shift={{json_encode(config('site.shifts'))}}></div>
        </div>
    </div>
@endsection
