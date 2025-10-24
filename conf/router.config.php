<?php

// Define the routes
$routes = [
    "/" => "HomeController@index",
    "/about" => "AboutController@index",
    "/manage" => "ReverseProxyController@index",
    "/manage/edit" => "ReverseProxyController@edit",
    "/manage/edit/{idreverseproxies}" => "ReverseProxyController@edit",
    "/manage/delete/{idreverseproxies}" => "ReverseProxyController@delete",
    "/login" => "AuthController@login",
    "/logout" => "AuthController@logout",
    "/contact" => "ContactController@index",
    "/users/details/{id}" => "UserController@show",
    "/users/details/{id}/{id2}/{id3}" => "UserController@show3",
];
