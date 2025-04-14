<?php
class BaseController {
    protected $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    protected function render($view, $data = []) {
        extract($data);
        
        include_once 'views/layouts/header.php';
        include_once "views/$view.php";
        include_once 'views/layouts/footer.php';
    }
    
    // tạo URL với đườg dẫn cơ sở
    protected function url($path) {
        return BASE_URL . $path;
    }

    // phương pháp chuyển hướng để sử dụng URL cơ sở
    protected function redirect($url) {
        header("Location: " . BASE_URL . $url);
        exit;
    }
    
    protected function generateRandomId($prefix, $length = 3) {
        $characters = '0123456789';
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $prefix . $randomString;
    }
}
?>

