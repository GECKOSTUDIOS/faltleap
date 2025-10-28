<?php

// Define the routes
$routes = [
  //"/" => "HomeController@index",
  "/" => "ReverseProxyController@index",
  "/about" => "AboutController@index",
  "/manage" => "ReverseProxyController@index",
  "/manage/edit" => "ReverseProxyController@edit",
  "/manage/edit/{idreverseproxies}" => "ReverseProxyController@edit",
  "/manage/delete/{idreverseproxies}" => "ReverseProxyController@delete",
  "/manage/deploy/{idreverseproxies}" => "ReverseProxyController@deploy",
  "/login" => "AuthController@login",
  "/logout" => "AuthController@logout",
  "/contact" => "ContactController@index",
  "/users" => "UsersController@index",
  "/users/edit/{id}" => "UsersController@edit",
  "/users/edit" => "UsersController@edit",
  "/users/delete/{id}" => "UsersController@delete",
];
