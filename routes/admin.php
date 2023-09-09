<?php


use App\Http\Controllers\Api\Admin\AdminMenuController;
use App\Http\Controllers\Api\Admin\AdminUserController;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\CommonController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\ToolsController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::post('/tools/uploadPic', [ToolsController::class, 'uploadPic']);

Route::get('/common/captcha/img', [CommonController::class, 'captcha']);

Route::get('/adminMenu/list/route', [AdminMenuController::class, 'route']);


Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/dashboard/contentData', [DashboardController::class, 'contentData']);
    Route::get('/dashboard/popular', [DashboardController::class, 'popular']);
    Route::get('/dashboard/announcement', [DashboardController::class, 'announcement']);

    Route::resource('/adminUser', AdminUserController::class);
    Route::get('/adminUser/list/export', [AdminUserController::class, 'export']);

    Route::resource('/adminMenu', AdminMenuController::class);
    Route::get('/adminMenu/list/tree', [AdminMenuController::class, 'tree']);
});
