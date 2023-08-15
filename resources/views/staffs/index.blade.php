@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="content-header">
            <div>
                <h2>社員一覧画面</h2>
            </div>
            <div class="breadcrumb">
                <a href="/">ホーム</a>
                <span>></span>
                <a href={{ route('staffs.index')}}>社員一覧</a>
            </div>
        </div>
        @include('layouts.flash-message')
        <div class="content-body">
            <button>
                <a href="{{ route('staffs.edit')}}" class="anchor-button">新規登録</a>
            </button>
            <table>
                <thead>
                <tr>
                    <th class="table-code">社員コード</th>
                    <th class="table-name">名前(カナ)</th>
                    <th class="table-name">名前</th>
                    <th class="table-name">役職</th>
                    <th class="table-date">入社日</th>
                    <th class="table-button">給与</th>
                    <th class="table-button">勤怠</th>
                    <th class="table-button">支給・控除</th>
                </tr>
                </thead>
                <tbody>
                @foreach($staffs->filter(function ($staff) {
                    return $staff->is_void === 0;
                }) as $staff)
                    <tr>
                        <td class="table-code"><a href="{{ route('staffs.edit', ['id' => $staff->id])}}">{{ $staff->code }}</a></td>
                        <td class="table-name">{{ $staff->name_kana }}</td>
                        <td class="table-name">{{ $staff->name }}</td>
                        <td class="table-name">{{ $staff->staff_types->name }}</td>
                        <td class="table-date">{{ $staff->haire_date }}</td>
                        <td class="table-button">
                            <button>
                                <a href="{{ route('staffs.display-payroll', ['id' => $staff->id])}}" class="anchor-button">給与明細</a>
                            </button>
                        </td>
                        <td class="table-button">
                            <button>
                                <a href="{{ route('staffs.display-attendances', ['id' => $staff->id])}}" class="anchor-button">勤怠一覧</a>
                            </button>
                        </td>
                        <td class="table-button">
                            <button>
                                <a href="{{ route('staffs.display-provision-and-deduction', ['id' => $staff->id])}}" class="anchor-button">支給・控除編集</a>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{Form::label('-','VOIDされた社員を表示',['class'=>'form-label'])}}
            {{Form::checkbox('-', '-', false, ['class'=>'js-open-table', 'id'=>'open-table-check'])}}
            <table class="hidden-table" style="display: none;">
                <tbody>
                @foreach($staffs->filter(function ($staff) {
                    return $staff->is_void === 1;
                }) as $staff)
                    <tr>
                        <td class="table-code"><a href="{{ route('staffs.edit', ['id' => $staff->id])}}">{{ $staff->code }}</a></td>
                        <td class="table-name">{{ $staff->name_kana }}</td>
                        <td class="table-name">{{ $staff->name }}</td>
                        <td class="table-name">{{ $staff->staff_types->name }}</td>
                        <td class="table-date">{{ $staff->haire_date }}</td>
                        <td class="table-button">
                            <button>
                                <a href="{{ route('staffs.display-payroll', ['id' => $staff->id])}}" class="anchor-button">給与明細</a>
                            </button>
                        </td>
                        <td class="table-button">
                            <button>
                                <a href="{{ route('staffs.display-attendances', ['id' => $staff->id])}}" class="anchor-button">勤怠一覧</a>
                            </button>
                        </td>
                        <td class="table-button">
                            <button>
                                <a href="{{ route('staffs.display-provision-and-deduction', ['id' => $staff->id])}}" class="anchor-button">支給・控除編集</a>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
