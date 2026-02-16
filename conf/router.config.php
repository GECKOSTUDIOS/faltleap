<?php

// Define the routes
$routes = [
  //"/" => "HomeController@index",
  "/" => ["ReverseProxyController@index", "auth"],
  "/about" => "AboutController@index",
  "/manage" => ["ReverseProxyController@index", "auth"],
  "/manage/edit" => ["ReverseProxyController@edit", "auth"],
  "/manage/edit/{idreverseproxies}" => ["ReverseProxyController@edit", "auth"],
  "/manage/delete/{idreverseproxies}" => ["ReverseProxyController@delete", "auth"],
  "/manage/deploy/{idreverseproxies}" => ["ReverseProxyController@deploy", "auth"],
  "/login" => "AuthController@login",
  "/logout" => "AuthController@logout",
  "/contact" => "ContactController@index",
  "/users" => ["UsersController@index", "auth"],
  "/users/edit/{id}" => ["UsersController@edit", "auth"],
  "/users/edit" => ["UsersController@edit", "auth"],
  "/users/delete/{id}" => ["UsersController@delete", "auth"],
];
