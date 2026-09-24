<?php
// public/index.php

// 1. بدء الجلسة
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. استدعاء إعدادات قاعدة البيانات والموجه
require_once '../config/database.php';
require_once '../core/Router.php';

// 3. إنشاء كائن الموجه
$router = new Router();

// مسارات المصادقة والتسجيل
$router->add('GET', '/register', ['AuthController', 'showRegister']);
$router->add('POST', '/register', ['AuthController', 'handleRegister']);
$router->add('GET', '/login', ['AuthController', 'showLogin']);
$router->add('POST', '/login', ['AuthController', 'handleLogin']);
$router->add('GET', '/logout', ['AuthController', 'logout']);

// مسارات المعرض الرئيسي
$router->add('GET', '/', ['PhotoController', 'index']);
$router->add('GET', '/photos', ['PhotoController', 'index']);

// مسارات رفع الصور
$router->add('GET', '/upload', ['PhotoController', 'create']);
$router->add('POST', '/upload', ['PhotoController', 'store']);

// مسارات تفاصيل الصورة والتعليقات والحذف
$router->add('GET', '/photo/{id}', ['PhotoController', 'show']);
$router->add('POST', '/photo/{id}/comment', ['PhotoController', 'addComment']);
$router->add('GET', '/photo/{id}/delete', ['PhotoController', 'delete']);

// 4. تشغيل الموجه
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $pdo);