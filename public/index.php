<?php
session_start();

require_once '../config/database.php';
require_once '../core/Router.php';

$router = new Router();

// مسارات التسجيل والدخول
$router->add('GET', '/register', ['AuthController', 'showRegister']);
$router->add('POST', '/register', ['AuthController', 'handleRegister']);
$router->add('GET', '/login', ['AuthController', 'showLogin']);
$router->add('POST', '/login', ['AuthController', 'handleLogin']);
$router->add('GET', '/logout', ['AuthController', 'logout']);
$router->add('GET', '/', ['PhotoController', 'index']);
$router->add('GET', '/photos', ['PhotoController', 'index']);
$router->add('GET', '/photo/{id}', ['PhotoController', 'show']);
// تشغيل الموجه
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $pdo);