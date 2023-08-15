@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="content-title">
            <div>
                <h2>シフトタイプ編集画面</h2>
            </div>
            <div class="breadcrumb">
                <a href="/">ホーム</a>
                <span>></span>
                <span>シフトタイプ編集画面</span>
            </div>
        </div>
        @include('layouts.flash-message')
        <div class="content-body">
            <div class="form-whole">
                {{Form::open(['url' => route('shiftTypes.edit'), 'method' => 'post'])}}
                <div class="form-buttons">
                    {{Form::submit('登録', ['class'=>'form-submit'])}}
                </div>
                <div hidden>
                    <table>
                        <tbody>
                        <tr class="js-mt-table-template" id="mt-new-row">
                            <td class="table-name">{{Form::text('.name','', ['class'=>'input-name', 'required' => 'required', 'disabled' => 'disabled', 'max' => 20])}}</td>
                            <td class="table-name">{{Form::time('.work_time_from', '00:01', ['class'=>'input-name', 'required' => 'required', 'disabled' => 'disabled'])}}</td>
                            <td class="table-name">{{Form::time('.work_time_to', '23:59', ['class'=>'input-name', 'required' => 'required', 'disabled' => 'disabled',])}}</td>
                            <td class="no-border-cell"><img src="{{asset('img/delete-row-icon.png')}}" class="opacity-icon js-delete-row-icon"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="form-table">
                    <table class="js-mt-table">
                        <thead>
                        <tr>
                            <th class="table-name">名前</th>
                            <th class="table-name">開始時間</th>
                            <th class="table-name">終了時間</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($shiftTypes as $shiftType)
                            <tr>
                                <td class="table-name">{{Form::text($shiftType->id . '.name', $shiftType->name, ['class'=>'input-name', 'required' => 'required', 'max' => 20])}}</td>
                                <td class="table-name">{{Form::time($shiftType->id . '.work_time_from', $shiftType->work_time_from, ['class'=>'input-name', 'required' => 'required'])}}</td>
                                <td class="table-name">{{Form::time($shiftType->id . '.work_time_to', $shiftType->work_time_to, ['class'=>'input-name', 'required' => 'required'])}}</td>
                            </tr>
                        @endforeach
                        <tr class="js-mt-last-row">
                            <td class="js-mt-add" id="add-button" ColSpan='3'>追加する</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                {{Form::close()}}
            </div>
        </div>
    </div>
@endsection
