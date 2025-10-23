<?php

// Define the routes
$routes = [
    "/" => "HomeController@index",
    "/about" => "AboutController@index",
    "/manage" => "ReverseProxyController@index",
    "/login" => "AuthController@login",
    "/logout" => "AuthController@logout",
    "/contact" => "ContactController@index",
    "/users/details/{id}" => "UserController@show",
    "/users/details/{id}/{id2}/{id3}" => "UserController@show3",
];
