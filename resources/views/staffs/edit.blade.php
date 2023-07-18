<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ToDo App</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
    @viteReactRefresh
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<main>
    <div>
        <h2>社員編集画面-{{$staff->exists ? '編集' : '新規'}}</h2>
    </div>
    @if (session('flash_message_success'))
        <div class="flash_message_success">
            {{ session('flash_message_success') }}
        </div>
    @endif
    @if (session('flash_message_fail'))
        <div class="flash_message_fail">
            {{ session('flash_message_fail') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ str_replace('field_name', 'カスタムラベル', $error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="form-whole">
        <div class="form-start">
            {{Form::open(['url' => route('staffs.create'), 'method' => 'post'])}}
            {{Form::token()}}
        </div>
        <div class="form-buttons">
            <a href=route('staffs.void') class="system-button button anchor-button">VOID</a>
            {{Form::submit('送信', ['class'=>'form-submit'])}}
        </div>
    </div>
    <div class="form-input">
        <div class="form-row display-flex">
            <div class="form-block">
                <h3>基本情報</h3>
                <div class="form-element hidden">
                    {{Form::hidden('id', $staff->id, ['id' => 'staff_id','data-staff-id' => $staff->id])}}
                </div>
                <div class="form-element">
                    {{Form::label('name_kana','名前(フリガナ)',['class'=>'form-label'])}}
                    {{Form::text('name_kana', $staff->name_kana, ['class'=>'input-name', 'required' => 'required', 'max' => 20])}}
                </div>
                <div class="form-element">
                    {{Form::label('name','名前',['class'=>'form-label'])}}
                    {{Form::text('name', $staff->name, ['class'=>'input-name', 'required' => 'required', 'max' => 20])}}
                </div>
                <div class="form-element">
                    {{Form::label('birthday','生年月日',['class'=>'form-label'])}}
                    {{Form::text('birthday', $staff->birthday, ['class'=>'input-date', 'required' => 'required', 'placeholder' => 'YYYY/MM/DD'])}}
                </div>
                <div class="form-element" class="form-label">
                    {{Form::label('sex_code','性別',['class'=>'form-label', 'required' => 'required'])}}
                    {{Form::select('sex_code', $sexes, $staff->sex_code)}}
                </div>
            </div>
            <div class="form-block">
                <h3>連絡先情報</h3>
                <div class="form-element">
                    {{Form::label('address','住所',['class'=>'form-label'])}}
                    {{Form::text('address', $staff->address, ['class'=>'input-address', 'max' => 100])}}
                </div>
                <div class="form-element">
                    {{Form::label('tel','電話番号',['class'=>'form-label'])}}
                    {{Form::text('tel', $staff->tel, ['class'=>'input-tel', 'max' => 20])}}
                </div>
                <div class="form-element">
                    {{Form::label('mail_address','メールアドレス',['class'=>'form-label'])}}
                    {{Form::text('mail_address', $staff->mail_address, ['class'=>'input-mail-address', 'max' => 100])}}
                </div>
            </div>
        </div>
        <div class="form-row display-flex">
            <div class="form-block">
                <h3>社員情報</h3>
                <div class="form-element">
                    {{Form::label('code','社員コード',['class'=>'form-label'])}}
                    {{Form::text('code', $staff->code, ['class'=>'input-code', 'disabled' => true])}}
                </div>
                <div class="form-element">
                    {{Form::label('staff_type_id','役職',['class'=>'form-label'])}}
                    {{Form::select('staff_type_id', $staffTypes, ['class'=>'input-mail-address', 'required' => 'required'])}}
                </div>
                <div class="form-element">
                    {{Form::label('hourly_wage','時給',['class'=>'form-label'])}}
                    {{Form::text('hourly_wage', floor($staff->hourly_wage), ['class'=>'input-money', 'required' => 'required'])}}
                    円
                </div>
                <div class="form-element">
                    {{Form::label('haire_date','入社日',['class'=>'form-label'])}}
                    {{Form::text('haire_date', $staff->haire_date, ['class'=>'input-date', 'required' => 'required', 'placeholder' => 'YYYY/MM/DD'])}}
                </div>
            </div>
            <div class="form-block">
                <h3>備考</h3>
                <div class="form-element">
                    {{Form::label('memo','備考',['class'=>'form-label'])}}
                    {{Form::textarea('memo', $staff->memo, ['class'=>'input-memo', 'max' => 20])}}
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-block-password">
                <h3>パスワード</h3>
                @if (!$staff->exist)
                    <div>
                        <p>新規登録の場合はサイトの初期設定パスワードが設定されます。</p>
                    </div>
                @endif
                <div class="display-flex">
                    <div class="form-element">
                        {{Form::label('password','パスワード',['class'=>'form-label'])}}
                        {{Form::text('-', '**********', ['class'=>'input-password', 'disabled' => true])}}
                    </div>
                    <div id="dialog-register-password" data-isNew="{{$staff->exists}}"></div>
                </div>
            </div>
        </div>
    </div>
    </div>
    {{Form::close()}}
</main>
</body>
</html>
