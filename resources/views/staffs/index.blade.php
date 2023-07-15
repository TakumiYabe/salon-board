<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ToDo App</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>
<body>
<main>
    <div>
        <div>
            <h2>社員管理画面</h2>
        </div>
        <button>
            <a href="{{ route('staffs.create')}}" class="anchor-button">新規登録</a>
        </button>
        <table>
            <thead>
            <tr>
                <th class="table-code">社員コード</th>
                <th class="table-name">名前(カナ)</th>
                <th class="table-name">名前</th>
                <th class="table-name">役職</th>
                <th class="table-date">入社日</th>
                <th class="table-button">勤怠</th>
            </tr>
            </thead>
            <tbody>
            @foreach($staffs as $staff)
            <tr class={{$staff->is_void ? "is_void" : ""}} >
                <td><a href="{{ route('staffs.edit', ['id' => $staff->id])}}">{{ $staff->code }}</a></td>
                <td>{{ $staff->name_kana }}</td>
                <td>{{ $staff->name }}</td>
                <td>{{ $staff->staff_types->name }}</td>
                <td>{{ $staff->haire_date }}</td>
                <td>勤怠</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
