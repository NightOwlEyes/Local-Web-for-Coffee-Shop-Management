<?php
session_start();
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'controllers/BaseController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/AdminController.php';
require_once 'controllers/MenuController.php';
require_once 'controllers/StaffController.php';
require_once 'controllers/OrderController.php';
require_once 'controllers/RevenueController.php';

// Update the request URI handling to account for the subdirectory
$request = $_SERVER['REQUEST_URI'];
$request = str_replace(BASE_URL, '', $request);
$request = strtok($request, '?');
if (empty($request)) {
    $request = '/';
}

// Define routes
$routes = [
    '/' => ['controller' => 'AuthController', 'action' => 'login'],
    '/login' => ['controller' => 'AuthController', 'action' => 'login'],
    '/logout' => ['controller' => 'AuthController', 'action' => 'logout'],
    '/quanly' => ['controller' => 'AdminController', 'action' => 'dashboard', 'auth' => 'admin'],
    '/danhsach' => ['controller' => 'MenuController', 'action' => 'index', 'auth' => 'any'],
    '/menu/add' => ['controller' => 'MenuController', 'action' => 'add', 'auth' => 'admin'],
    '/menu/edit' => ['controller' => 'MenuController', 'action' => 'edit', 'auth' => 'admin'],
    '/menu/delete' => ['controller' => 'MenuController', 'action' => 'delete', 'auth' => 'admin'],
    '/nhanvien' => ['controller' => 'StaffController', 'action' => 'index', 'auth' => 'admin'],
    '/nhanvien/add' => ['controller' => 'StaffController', 'action' => 'add', 'auth' => 'admin'],
    '/nhanvien/edit' => ['controller' => 'StaffController', 'action' => 'edit', 'auth' => 'admin'],
    '/nhanvien/delete' => ['controller' => 'StaffController', 'action' => 'delete', 'auth' => 'admin'],
    '/nhanvien/detail' => ['controller' => 'StaffController', 'action' => 'detail', 'auth' => 'admin'],
    '/nhanvien/view' => ['controller' => 'StaffController', 'action' => 'view', 'auth' => 'admin'],
    '/thanhtoan' => ['controller' => 'OrderController', 'action' => 'checkout', 'auth' => 'staff'],
    '/hoadon' => ['controller' => 'OrderController', 'action' => 'hoadon', 'auth' => 'any'],
    '/order/process' => ['controller' => 'OrderController', 'action' => 'process', 'auth' => 'staff'],
    '/order/addToCartBulk' => ['controller' => 'OrderController', 'action' => 'addToCartBulk', 'auth' => 'staff'],
    '/order/getCartCount' => ['controller' => 'OrderController', 'action' => 'getCartCount', 'auth' => 'staff'],
    '/doanhthu' => ['controller' => 'RevenueController', 'action' => 'index', 'auth' => 'admin'],
    '/doanhthu/monthly' => ['controller' => 'RevenueController', 'action' => 'monthly', 'auth' => 'admin'],
    '/doanhthu/detail' => ['controller' => 'RevenueController', 'action' => 'detail', 'auth' => 'admin'],

    // --- Món ăn ---
    '/menu/search' => ['controller' => 'MenuController', 'action' => 'search', 'auth' => 'any'],
    '/menu/apiList' => ['controller' => 'MenuController', 'action' => 'apiList'],
    '/menu/apiDelete' => ['controller' => 'MenuController', 'action' => 'apiDelete'],

    // --- Nhân viên ---
    '/nhanvien/apiList' => ['controller' => 'StaffController', 'action' => 'apiList'],
    '/nhanvien/apiDelete' => ['controller' => 'StaffController', 'action' => 'apiDelete'],

    // --- Đơn hàng / thanh toán ---
    '/order/view' => ['controller' => 'OrderController', 'action' => 'view', 'auth' => 'staff'],
    '/order/apiList' => ['controller' => 'OrderController', 'action' => 'apiList'],
    '/order/apiDelete' => ['controller' => 'OrderController', 'action' => 'apiDelete'],

    // --- Doanh thu ---
    '/doanhthu/apiOverview' => ['controller' => 'RevenueController', 'action' => 'apiOverview'],

    // --- Cài đặt DB ---
    '/setup/database' => ['controller' => 'OrderController', 'action' => 'setupDatabase']
];

// Route the request
if (array_key_exists($request, $routes)) {
    $route = $routes[$request];
    $controllerName = $route['controller'];
    $actionName = $route['action'];
    
    // Check authentication
    if (isset($route['auth'])) {
        if ($route['auth'] == 'admin' && (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin')) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        } elseif ($route['auth'] == 'staff' && (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'staff')) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        } elseif ($route['auth'] == 'any' && !isset($_SESSION['user_role'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }
    
    $controller = new $controllerName();
    $controller->$actionName();
} else if ($request === '/setup/database') {
    $controller = new OrderController();
    $controller->setupDatabase();
} else {
    // 404 Not Found
    echo '404 Not Found';
}
?>