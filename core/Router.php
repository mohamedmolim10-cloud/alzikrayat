<?php
class Router {
    private $routes = [];
    // تسجيل مسار جديد
    public function add($method, $path, $action) {
        $this->routes[strtoupper($method)][$path] = $action;
    }
    // توجيه الطلب للمتحكم المناسب
    public function dispatch($method, $uri, $pdo) {
        // استخراج الرابط وتنظيفه
        $path = parse_url($uri, PHP_URL_PATH);
        $path = str_replace('/alzikrayat/public', '', $path);
        $path = rtrim($path, '/') ?: '/';
        $method = strtoupper($method);
       // فحص وجود المسار
        if (isset($this->routes[$method][$path])) {
            [$controllerName, $actionName] = $this->routes[$method][$path];

            require_once "../controllers/{$controllerName}.php";
            $controller = new $controllerName($pdo);
            $controller->$actionName();
            return;
        }
        // إذا لم يتم العثور على المسار
        http_response_code(404);
        echo "<h2 style='text-align:center; margin-top:50px;'>404 - الصفحة غير موجودة</h2>";
    }
}