// 性別
php artisan migrate:refresh  --step=1 --path=/database/migrations/2023_06_30_041710_create_sexes_table.php
php artisan db:seed --class=SexesTableSeeder

// スタッフ
php artisan migrate:refresh  --step=1 --path=/database/migrations/2023_06_30_041656_create_staff_types_table.php
php artisan db:seed --class=StaffTypesTableSeeder
php artisan migrate:refresh  --step=1 --path=/database/migrations/2023_06_30_041647_create_staffs_table.php
php artisan db:seed --class=StaffsTableSeeder

// シフト
php artisan migrate:refresh  --step=1 --path=/database/migrations/2023_06_30_041618_create_shift_submissions_table.php
php artisan migrate:refresh  --step=1 --path=/database/migrations/2023_06_30_041626_create_shifts_table.php
php artisan migrate:refresh  --step=1 --path=/database/migrations/2023_06_30_041638_create_shift_types_table.php
php artisan db:seed --class=ShiftTypesTableSeeder

// お知らせ
php artisan migrate:refresh  --step=1 --path=/database/migrations/2023_06_30_041528_create_news_table.php

// 勤怠支給控除
php artisan migrate:refresh  --step=1 --path=/database/migrations/2023_06_30_041727_create_attendances_table.php
php artisan migrate:refresh  --step=1 --path=/database/migrations/2023_06_30_041741_create_provisions_table.php
php artisan migrate:refresh  --step=1 --path=/database/migrations/2023_06_30_041757_create_deductions_table.php

// 顧客
php artisan migrate:refresh  --step=1 --path=/database/migrations/2023_06_30_041932_create_customer_categories_table.php
php artisan db:seed --class=CustomerCategoriesTableSeeder
php artisan migrate:refresh  --step=1 --path=/database/migrations/2023_06_30_041939_create_customers_table.php
php artisan db:seed --class=CustomersTableSeeder
php artisan migrate:refresh  --step=1 --path=/database/migrations/2023_06_30_041950_create_customer_details_table.php

// 割引
php artisan migrate:refresh  --step=1 --path=/database/migrations/2023_06_30_042022_create_discount_types_table.php
php artisan migrate:refresh  --step=1 --path=/database/migrations/2023_06_30_042013_create_discounts_table.php
php artisan migrate:refresh  --step=1 --path=/database/migrations/2023_06_30_042005_create_customer_discounts_table.php

// コスト
php artisan migrate:refresh  --step=1 --path=/database/migrations/2023_06_30_042034_create_costs_table.php

// 予約
php artisan migrate:refresh  --step=1 --path=/database/migrations/2023_06_30_041823_create_reserve_courses_table.php
php artisan migrate:refresh  --step=1 --path=/database/migrations/2023_06_30_041806_create_reserves_table.php
php artisan migrate:refresh  --step=1 --path=/database/migrations/2023_06_30_041844_create_reserve_discounts_table.php

// 外部キー
php artisan migrate:refresh  --step=1 --path=/database/migrations/2023_07_01_160359_add_foreign_key_to_sexes.php
