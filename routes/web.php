<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function () {
    
    Route::get('/dashboard','DashboardController@index')->name('admin.dashboard');
    
    //Permission Route
    Route::get('/permission','PermissionsController@index')->name('permission')->middleware('role:Admin');
    Route::get('/permission/create','PermissionsController@create')->name('permission.create')->middleware('role:Admin');
    Route::get('/permission/getData','PermissionsController@getData')->name('permission.getData')->middleware('role:Admin');
    Route::post('/permission/store','PermissionsController@store')->name('permission.store')->middleware('role:Admin');
    Route::get('/permission/edit/{id}','PermissionsController@edit')->name('permission.edit')->middleware('role:Admin');
    Route::post('/permission/update/{id}','PermissionsController@update')->name('permission.update')->middleware('role:Admin');
    Route::get('/delete-permission/{id}','PermissionsController@destroy')->name('permission.destroy')->middleware('role:Admin');
    
    
    //Roles Routes
    Route::get('/role','RolesController@index')->name('role')->middleware('role:Admin|Manager');
    Route::get('/role/create','RolesController@create')->name('role.create')->middleware('role:Admin|Manager');
    Route::get('/role/getData','RolesController@getData')->name('role.getData')->middleware('role:Admin|Manager');
    Route::post('/role/store','RolesController@store')->name('role.store')->middleware('role:Admin|Manager');
    Route::get('/role/edit/{id}','RolesController@edit')->name('role.edit')->middleware('role:Admin|Manager');
    Route::post('/role/update/{id}','RolesController@update')->name('role.update')->middleware('role:Admin|Manager');
    Route::get('/delete-role/{id}','RolesController@destroy')->name('role.destroy')->middleware('role:Admin|Manager');
    
    //Users Routes
    
    Route::get('/user','UsersController@index')->name('user')->middleware('role:Admin|Manager');
    Route::get('/user/create','UsersController@create')->name('user.create')->middleware('role:Admin|Manager');
    Route::get('/user/getData','UsersController@getData')->name('user.getData')->middleware('role:Admin|Manager');
    Route::post('/user/store','UsersController@store')->name('user.store')->middleware('role:Admin|Manager');
    Route::get('/user/edit/{id}','UsersController@edit')->name('user.edit')->middleware('role:Admin|Manager');
    Route::post('/user/update/{id}','UsersController@update')->name('user.update')->middleware('role:Admin|Manager');
    Route::get('/delete-user/{id}','UsersController@destroy')->name('user.destroy')->middleware('role:Admin|Manager');
    
    
    //Emplyee Routes
    Route::get('/employee','EmployeController@index')->name('employee')->middleware('role:Admin|Manager');
    Route::get('/employee/create','EmployeController@create')->name('employee.create')->middleware('role:Admin|Manager');
    Route::get('/employee/getData','EmployeController@getData')->name('employee.getData')->middleware('role:Admin|Manager');
    Route::post('/employee/store','EmployeController@store')->name('employee.store')->middleware('role:Admin|Manager');
    Route::get('/employee/edit/{id}','EmployeController@edit')->name('employee.edit')->middleware('role:Admin|Manager');
    Route::post('/employee/update/{id}','EmployeController@update')->name('employee.update')->middleware('role:Admin|Manager');
    Route::get('/delete-employee/{id}','EmployeController@destroy')->name('employee.destroy')->middleware('role:Admin|Manager');
    Route::get('/employee/delete_image/{id}','EmployeController@delete_image')->name('employee.delete_image');
    
    //Employee Attendance Route
    Route::get('/attendance','AttendanceController@index')->name('attendance')->middleware('role:Admin|Manager');
    Route::get('/attendance/create','AttendanceController@create')->name('attendance.create')->middleware('role:Admin|Manager');
    Route::get('/attendance/getData','AttendanceController@getData')->name('attendance.getData')->middleware('role:Admin|Manager');
    Route::post('/attendance/store','AttendanceController@store')->name('attendance.store')->middleware('role:Admin|Manager');
    Route::get('/attendance/edit/{date}','AttendanceController@edit')->name('attendance.edit')->middleware('role:Admin|Manager');
    Route::get('/delete-attendance/{date}','AttendanceController@destroy')->name('attendance.destroy')->middleware('role:Admin|Manager');
    
});