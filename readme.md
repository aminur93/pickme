## About PickMe

-First update composer
- composer update

-Second migrate database
- php artisan migrate

-Third if you are login as admin
- php artisan db:seed --class=PermissionTableSeeder
- php artisan db:seed --class=CreateAdminUserSeeder

