<?php

return [
    'required' => ':attributeは必須です。',
    'email' => ':attributeは正しいメールアドレスの形式で入力してください。',
    'numeric' => ':attributeは数字のみで入力してください。',
    'string' => ':attributeは文字列で入力してください。',
    'max' => [
        'string' => ':attributeは:max文字以下で入力してください。',
        'numeric' => ':attributeは:max以下で入力してください。',
    ],

    'attribute' => [
        'id' => 'ID',
        'name_kana' => '名前(フリガナ)',
        'name' => '名前',
        'birthday_year' => '生年月日(年)',
        'birthday_month' => '生年月日(月)',
        'birthday_day' => '生年月日(日)',
        'sex_code' => '性別',
        'address' => '住所',
        'tel' => '電話',
        'mail_address' => 'メールアドレス',
        'password' => 'パスワード',
        'staff_type_id' => 'スタッフID',
        'hourly_wage' => '時給',
        'haire_date_year' => '入社日(年)',
        'haire_date_month' => '入社日(月)',
        'haire_date_day' => '入社日(日)',
        'memo' => '備考',
    ],
];
