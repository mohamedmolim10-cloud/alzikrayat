<?php
/**
 * كلاس التوجيه اليدوي (Manual Router)
 * مسؤول عن مطابقة المسارات ديناميكياً باستخدام التعابير النمطية (Regex)
 * واستخراج المعاملات مثل {id} وتمريرها للمتحكمات
 */

class Router {
    private $routes = [];

    public function add($method, $path, $handler) {
        $this->routes[] = [
            'method'  => strtoupper($method),
            'path'    => $path,
            'handler' => $handler
        ];
    }

    public function dispatch($requestMethod, $requestUri, $pdo = null) {
        // تنظيف الرابط من معاملات الاستعلام
        $uri = parse_url($requestUri, PHP_URL_PATH);

        // إزالة مسار المجلد تلقائياً مهما كان موقعه في السيرفر
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptName !== '/' && $scriptName !== '\\' && strpos($uri, $scriptName) === 0) {
            $uri = substr($uri, strlen($scriptName));
        }

        if ($uri === '' || $uri === false) {
            $uri = '/';
        }

        $requestMethod = strtoupper($requestMethod);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            // تحويل المعامل {id} إلى Regex
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // إبقاء قيمة المعرف فقط

                [$controllerName, $action] = $route['handler'];

                // تضمين ملف المتحكم
                $controllerPath = __DIR__ . "/../controllers/{$controllerName}.php";
                if (file_exists($controllerPath)) {
                    require_once $controllerPath;
                } else {
                    http_response_code(500);
                    echo "خطأ: ملف المتحكم {$controllerName} غير موجود.";
                    return;
                }

                // إنشاء كائن المتحكم وتمرير اتصال قاعدة البيانات واستدعاء الدالة
                $controllerInstance = new $controllerName($pdo);
                call_user_func_array([$controllerInstance, $action], $matches);
                return;
            }
        }

        // في حال عدم العثور على المسار
        http_response_code(404);
        echo "
        <div style='font-family: Arial, sans-serif; text-align: center; margin-top: 80px; direction: rtl;'>
            <h1 style='font-size: 50px; color: #dc3545;'>404</h1>
            <h2 style='color: #333;'>الصفحة غير موجودة</h2>
            <p style='color: #666;'>عذراً، الرابط المطلوب غير مسجل في النظام.</p>
            <a href='/alzikrayat/public/photos' style='display: inline-block; margin-top: 15px; padding: 10px 20px; background-color: #0d6efd; color: white; text-decoration: none; border-radius: 6px;'>العودة للمعرض الرئيسي</a>
        </div>
        ";
    }
}