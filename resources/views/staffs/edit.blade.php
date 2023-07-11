<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ToDo App</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
</head>
<body>
<main>
    <div>
        <h2>社員編集画面-{{$staff->exists ? '編集' : '新規'}}</h2>
    </div>
    {{Form::open(['url' => route('staffs.create'), 'method' => 'post'])}}
    {{Form::token()}}
    {{Form::submit('送信')}}
    <div class="form-block">
        <h3>基本情報</h3>
        <div class="form-element">
            {{Form::label('name_kana','名前(フリガナ)',['class'=>'form-label'])}}
            {{Form::text('name_kana', null, ['class'=>'input-name'])}}
        </div>
        <div class="form-element">
            {{Form::label('name','名前',['class'=>'form-label'])}}
            {{Form::text('name', null, ['class'=>'input-name'])}}
        </div>
        <div class="form-element">
            {{Form::label('birthday','生年月日',['class'=>'form-label'])}}
            {{Form::select('birthday_year', range(1950, 2050), ['class'=>'input-date-year'])}}
            {{Form::select('birthday_month', range(1, 12), ['class'=>'input-date-month'])}}
            {{Form::select('birthday_day', range(1, 31), ['class'=>'input-date-day'])}}
        </div>
        <div class="form-element" class="form-label">
            {{Form::label('sex_code','性別',['class'=>'form-label'])}}
            {{Form::select('sex_code', $sexes)}}
        </div>
    </div>
    <div class="form-block">
        <h3>連絡先情報</h3>
        <div class="form-element">
            {{Form::label('address','住所',['class'=>'form-label'])}}
            {{Form::text('address', null, ['class'=>'input-address'])}}
        </div>
        <div class="form-element">
            {{Form::label('tel','電話番号',['class'=>'form-label'])}}
            {{Form::text('tel', null, ['class'=>'input-tel'])}}
        </div>
        <div class="form-element">
            {{Form::label('mail_address','メールアドレス',['class'=>'form-label'])}}
            {{Form::text('mail_address', null, ['class'=>'input-mail-address'])}}
        </div>
    </div>
    <div class="form-block">
        <h3>社員情報</h3>

    </div>
    <div class="form-block">
        <h3>備考</h3>
    </div>
    {{Form::close()}}
</main>
</body>
</html>
