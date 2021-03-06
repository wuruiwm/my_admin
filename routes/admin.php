<?php

/*
|--------------------------------------------------------------------------
| 后台路由
|--------------------------------------------------------------------------
|
| 统一命名空间 Admin
| 统一前缀 admin
| 用户认证统一使用 auth 中间件
| 权限认证统一使用 permission:权限名称
|
*/

/*
|--------------------------------------------------------------------------
| 用户登录、退出、更改密码
|--------------------------------------------------------------------------
*/
Route::group(['namespace'=>'Admin','prefix'=>'admin/user'],function (){
    //登录
    Route::get('login','UserController@showLoginForm')->name('admin.user.loginForm');
    Route::post('login','UserController@login')->name('admin.user.login');
    //退出
    Route::get('logout','UserController@logout')->name('admin.user.logout')->middleware('auth');
    //更改密码
    Route::get('change_my_password_form','UserController@changeMyPasswordForm')->name('admin.user.changeMyPasswordForm')->middleware('auth');
    Route::post('change_my_password','UserController@changeMyPassword')->name('admin.user.changeMyPassword')->middleware('auth');
});

/*
|--------------------------------------------------------------------------
| 后台公共页面
|--------------------------------------------------------------------------
*/
Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>'auth'],function (){
    //后台布局
    Route::get('/','IndexController@layout')->name('admin.layout');
    //后台首页
    Route::get('/index','IndexController@index')->name('admin.index');
});


/*
|--------------------------------------------------------------------------
| 系统管理模块
|--------------------------------------------------------------------------
*/
Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>['auth','permission:system']],function (){

    //用户管理
    Route::group(['middleware'=>['permission:system.user']],function (){
        Route::get('user','UserController@index')->name('admin.user');
        Route::get('user/data','UserController@data')->name('admin.user.data');
        //添加
        Route::get('user/create','UserController@create')->name('admin.user.create')->middleware('permission:system.user.create');
        Route::post('user/store','UserController@store')->name('admin.user.store')->middleware('permission:system.user.create');
        //编辑
        Route::get('user/{id}/edit','UserController@edit')->name('admin.user.edit')->middleware('permission:system.user.edit');
        Route::put('user/{id}/update','UserController@update')->name('admin.user.update')->middleware('permission:system.user.edit');
        //删除
        Route::delete('user/destroy','UserController@destroy')->name('admin.user.destroy')->middleware('permission:system.user.destroy');
        //分配角色
        Route::get('user/{id}/role','UserController@role')->name('admin.user.role')->middleware('permission:system.user.role');
        Route::put('user/{id}/assignRole','UserController@assignRole')->name('admin.user.assignRole')->middleware('permission:system.user.role');
        //分配权限
        Route::get('user/{id}/permission','UserController@permission')->name('admin.user.permission')->middleware('permission:system.user.permission');
        Route::put('user/{id}/assignPermission','UserController@assignPermission')->name('admin.user.assignPermission')->middleware('permission:system.user.permission');
    });

    //角色管理
    Route::group(['middleware'=>'permission:system.role'],function (){
        Route::get('role','RoleController@index')->name('admin.role');
        Route::get('role/data','RoleController@data')->name('admin.role.data');
        //添加
        Route::get('role/create','RoleController@create')->name('admin.role.create')->middleware('permission:system.role.create');
        Route::post('role/store','RoleController@store')->name('admin.role.store')->middleware('permission:system.role.create');
        //编辑
        Route::get('role/{id}/edit','RoleController@edit')->name('admin.role.edit')->middleware('permission:system.role.edit');
        Route::put('role/{id}/update','RoleController@update')->name('admin.role.update')->middleware('permission:system.role.edit');
        //删除
        Route::delete('role/destroy','RoleController@destroy')->name('admin.role.destroy')->middleware('permission:system.role.destroy');
        //分配权限
        Route::get('role/{id}/permission','RoleController@permission')->name('admin.role.permission')->middleware('permission:system.role.permission');
        Route::put('role/{id}/assignPermission','RoleController@assignPermission')->name('admin.role.assignPermission')->middleware('permission:system.role.permission');
    });

    //权限管理
    Route::group(['middleware'=>'permission:system.permission'],function (){
        Route::get('permission','PermissionController@index')->name('admin.permission');
        Route::get('permission/data','PermissionController@data')->name('admin.permission.data');
        //添加
        Route::get('permission/create','PermissionController@create')->name('admin.permission.create')->middleware('permission:system.permission.create');
        Route::post('permission/store','PermissionController@store')->name('admin.permission.store')->middleware('permission:system.permission.create');
        //编辑
        Route::get('permission/{id}/edit','PermissionController@edit')->name('admin.permission.edit')->middleware('permission:system.permission.edit');
        Route::put('permission/{id}/update','PermissionController@update')->name('admin.permission.update')->middleware('permission:system.permission.edit');
        //删除
        Route::delete('permission/destroy','PermissionController@destroy')->name('admin.permission.destroy')->middleware('permission:system.permission.destroy');
    });

    //配置组
    Route::group(['middleware'=>'permission:system.config_group'],function (){
        Route::get('config_group','ConfigGroupController@index')->name('admin.config_group');
        Route::get('config_group/data','ConfigGroupController@data')->name('admin.config_group.data');
        //添加
        Route::get('config_group/create','ConfigGroupController@create')->name('admin.config_group.create')->middleware('permission:system.config_group.create');
        Route::post('config_group/store','ConfigGroupController@store')->name('admin.config_group.store')->middleware('permission:system.config_group.create');
        //编辑
        Route::get('config_group/{id}/edit','ConfigGroupController@edit')->name('admin.config_group.edit')->middleware('permission:system.config_group.edit');
        Route::put('config_group/{id}/update','ConfigGroupController@update')->name('admin.config_group.update')->middleware('permission:system.config_group.edit');
        //删除
        Route::delete('config_group/destroy','ConfigGroupController@destroy')->name('admin.config_group.destroy')->middleware('permission:system.config_group.destroy');
    });

    //配置项
    Route::group(['middleware'=>'permission:system.configuration'],function (){
        Route::get('configuration','ConfigurationController@index')->name('admin.configuration');
        //添加
        Route::get('configuration/create','ConfigurationController@create')->name('admin.configuration.create')->middleware('permission:system.configuration.create');
        Route::post('configuration/store','ConfigurationController@store')->name('admin.configuration.store')->middleware('permission:system.configuration.create');
        //编辑
        Route::put('configuration/update','ConfigurationController@update')->name('admin.configuration.update')->middleware('permission:system.configuration.edit');
        //删除
        Route::delete('configuration/destroy','ConfigurationController@destroy')->name('admin.configuration.destroy')->middleware('permission:system.configuration.destroy');
        //配置项上传图片
        Route::post('configuration/upload','ConfigurationController@upload')->name('admin.configuration.upload')->middleware('permission:system.configuration.create');
    });

    //登录日志
    Route::group(['middleware'=>'permission:system.login_log'],function (){
        Route::get('login_log','LoginLogController@index')->name('admin.login_log');
        Route::get('login_log/data','LoginLogController@data')->name('admin.login_log.data');
        Route::delete('login_log/destroy','LoginLogController@destroy')->name('admin.login_log.destroy');
    });

});

/*   ↑后台框架路由↑   */
/***********************************分割线*************************************/
/*   ↓业务逻辑路由↓   */

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'auth'],function(){
    //账号密码管理
    Route::group(['prefix'=>'password','middleware' => 'permission:password'],function(){
        Route::get('index','PasswordController@index')->name('admin.password.index')->middleware('permission:password.index');
        Route::get('list','PasswordController@list')->name('admin.password.list')->middleware('permission:password.index');
        Route::post('delete','PasswordController@delete')->name('admin.password.delete')->middleware('permission:password.delete');
        Route::post('edit','PasswordController@edit')->name('admin.password.edit')->middleware('permission:password.edit');
        Route::post('create','PasswordController@create')->name('admin.password.create')->middleware('permission:password.create');
    });
    //SSH管理
    Route::group(['prefix'=>'ssh','middleware' => 'permission:ssh'],function(){
        Route::get('index','SshController@index')->name('admin.ssh.index')->middleware('permission:ssh.index');
        Route::get('list','SshController@list')->name('admin.ssh.list')->middleware('permission:ssh.index');
        Route::post('delete','SshController@delete')->name('admin.ssh.delete')->middleware('permission:ssh.delete');
        Route::post('edit','SshController@edit')->name('admin.ssh.edit')->middleware('permission:ssh.edit');
        Route::post('create','SshController@create')->name('admin.ssh.create')->middleware('permission:ssh.create');
    });
    //短链
    Route::group(['prefix'=>'short/links','middleware' => 'permission:short_links'],function(){
        Route::get('index','ShortLinksController@index')->name('admin.short_links.index')->middleware('permission:short_links.index');
        Route::get('list','ShortLinksController@list')->name('admin.short_links.list')->middleware('permission:short_links.index');
        Route::post('delete','ShortLinksController@delete')->name('admin.short_links.delete')->middleware('permission:short_links.delete');
        Route::post('edit','ShortLinksController@edit')->name('admin.short_links.edit')->middleware('permission:short_links.edit');
        Route::post('create','ShortLinksController@create')->name('admin.short_links.create')->middleware('permission:short_links.create');
        Route::get('rand/tail','ShortLinksController@randTail')->name('admin.short_links.rand_tail')->middleware('permission:short_links.index');
    });
    //SSL证书
    Route::group(['prefix'=>'ssl','middleware' => 'permission:ssl'],function(){
        Route::get('index','SslController@index')->name('admin.ssl.index')->middleware('permission:ssl.index');
        Route::get('download','SslController@download')->name('admin.ssl.download')->middleware('permission:ssl.download');
    });
    //FRP域名跳转
    Route::group(['prefix'=>'frp','middleware' => 'permission:frp'],function(){
        Route::get('index','FrpController@index')->name('admin.frp.index')->middleware('permission:frp.index');
        Route::get('list','FrpController@list')->name('admin.frp.list')->middleware('permission:frp.index');
        Route::post('delete','FrpController@delete')->name('admin.frp.delete')->middleware('permission:frp.delete');
        Route::post('edit','FrpController@edit')->name('admin.frp.edit')->middleware('permission:frp.edit');
        Route::post('create','FrpController@create')->name('admin.frp.create')->middleware('permission:frp.create');
        Route::post('https/switch','FrpController@httpsSwitch')->name('admin.frp.https_switch')->middleware('permission:frp.edit');
    });
    //提取中文翻译
    Route::group(['prefix'=>'translate','middleware' => 'permission:translate'],function(){
        Route::get('index','TranslateController@index')->name('admin.translate.index')->middleware('permission:translate.index');
        Route::post('translate','TranslateController@translate')->name('admin.translate.translate')->middleware('permission:translate.index');
    });
    //microsoft office365 自助注册 邀请码 管理
    Route::group(['prefix'=>'microsoft','middleware' => 'permission:microsoft'],function(){
        Route::get('index','MicrosoftController@index')->name('admin.microsoft.index')->middleware('permission:microsoft.index');
        Route::get('list','MicrosoftController@list')->name('admin.microsoft.list')->middleware('permission:microsoft.index');
        Route::post('delete','MicrosoftController@delete')->name('admin.microsoft.delete')->middleware('permission:microsoft.delete');
        Route::post('create','MicrosoftController@create')->name('admin.microsoft.create')->middleware('permission:microsoft.create');
        Route::post('active','MicrosoftController@active')->name('admin.microsoft.active')->middleware('permission:microsoft.active');
        Route::post('inactive','MicrosoftController@inactive')->name('admin.microsoft.inactive')->middleware('permission:microsoft.inactive');
    });
});
