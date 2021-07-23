<?php

Route::get('/admin-login', function(){
    return redirect(route('welcome'), 301);
});

Route::post('/dashboard', function(){
    return redirect(route('welcome'), 301);
});