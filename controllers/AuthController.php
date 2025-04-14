<?php
class AuthController extends BaseController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($username) || empty($password)) {
                $error = 'Vui lòng nhập tên đăng nhập và mật khẩu';
                $this->render('login', ['error' => $error]);
                return;
            }
            
            $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['staff_id'] = $user['staff_id'];
                    
                    if ($user['role'] === 'admin') {
                        $this->redirect('/quanly');
                    } else {
                        $this->redirect('/danhsach');
                    }
                } else {
                    $error = 'Mật khẩu không đúng';
                    $this->render('login', ['error' => $error]);
                }
            } else {
                $error = 'Tên đăng nhập không tồn tại';
                $this->render('login', ['error' => $error]);
            }
        } else {
            if (isset($_SESSION['user_id'])) {
                if ($_SESSION['user_role'] === 'admin') {
                    $this->redirect('/quanly');
                } else {
                    $this->redirect('/danhsach');
                }
            }
            
            $this->render('login');
        }
    }
    
    public function logout() {
        session_destroy();
        $this->redirect('/login');
    }
}
?>

