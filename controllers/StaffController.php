<?php
require_once __DIR__ . '/../models/StaffModel.php';

class StaffController extends BaseController {
    private $staffModel;

    public function __construct() {
        parent::__construct();
        $this->staffModel = new StaffModel($this->db);
    }

    public function index() {
        $staffList = $this->staffModel->getAllStaff();
        $this->render('nhanvien', [
            'staffList' => $staffList
        ]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            if (empty($password)) {
                echo "Lỗi: Mật khẩu là bắt buộc.";
                return;
            }

            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image = $this->staffModel->handleImageUpload($_FILES['image']);
            }

            $staffId = $this->staffModel->generateStaffId();
            $username = $this->staffModel->generateUsername($_POST['name'], $staffId);

            $data = [
                'staff_id' => $staffId,
                'name' => $_POST['name'] ?? '',
                'dob' => $_POST['dob'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'email' => $_POST['email'] ?? '',
                'id_card' => $_POST['id_card'] ?? '',
                'hire_date' => $_POST['hire_date'] ?? date('Y-m-d'),
                'image' => $image,
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ];

            try {
                if ($this->staffModel->addStaff($data)) {
                    $this->redirect('/nhanvien');
                } else {
                    echo "Error adding staff";
                }
            } catch (Exception $e) {
                echo "Error adding staff: " . $e->getMessage();
            }
        } else {
            $this->redirect('/nhanvien');
        }
    }

    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $data = [
                'name' => $_POST['name'] ?? '',
                'dob' => $_POST['dob'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'email' => $_POST['email'] ?? '',
                'id_card' => $_POST['id_card'] ?? '',
                'hire_date' => $_POST['hire_date'] ?? date('Y-m-d'),
                'image' => '',
                'password' => $_POST['password'] ?? '' // Add password to data array
            ];

            // Only process image if a new one is uploaded
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $data['image'] = $this->staffModel->handleImageUpload($_FILES['image']);
            }

            try {
                if ($this->staffModel->updateStaff($id, $data)) {
                    $_SESSION['success'] = 'Cập nhật thông tin nhân viên thành công';
                    $this->redirect('/nhanvien/detail?id=' . $id);
                    return;
                } else {
                    $_SESSION['error'] = 'Không thể cập nhật thông tin nhân viên';
                }
            } catch (Exception $e) {
                $_SESSION['error'] = 'Lỗi khi cập nhật nhân viên: ' . $e->getMessage();
            }
            $this->redirect('/nhanvien');
        } else {
            $id = $_GET['id'] ?? 0;
            if ($id <= 0) {
                $this->redirect('/nhanvien');
                return;
            }

            $staff = $this->staffModel->getStaffById($id);
            if (!$staff) {
                $this->redirect('/nhanvien');
                return;
            }

            $this->render('nhanvien_edit', ['staff' => $staff]);
        }
    }

    public function delete() {
        if (!isset($_GET['id'])) {
            $this->redirect('/nhanvien');
            return;
        }

        $id = $_GET['id'];
        try {
            // Fetch the staff to get the image path
            $query = "SELECT image FROM staff WHERE staff_id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            $staff = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($staff && !empty($staff['image'])) {
                $imagePath = __DIR__ . '/../../uploads/staff/' . $staff['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath); // Delete the image file
                }
            }

            if ($this->staffModel->deleteStaff($id)) {
                $_SESSION['success'] = 'Xóa nhân viên thành công';
            } else {
                $_SESSION['error'] = 'Không thể xóa nhân viên';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi khi xóa nhân viên: ' . $e->getMessage();
        }
        
        $this->redirect('/nhanvien');
    }

    public function view() {
        if (!isset($_GET['id'])) {
            echo json_encode(['error' => 'ID không hợp lệ']);
            return;
        }

        $id = $_GET['id'];
        $staff = $this->staffModel->getStaffById($id);
        
        if (!$staff) {
            echo json_encode(['error' => 'Không tìm thấy nhân viên']);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode($staff);
    }

    public function detail() {
        if (!isset($_GET['id'])) {
            $this->redirect('/nhanvien');
            return;
        }

        $id = $_GET['id'];
        $staff = $this->staffModel->getStaffById($id);
        
        if (!$staff) {
            $this->redirect('/nhanvien');
            return;
        }

        $this->render('nhanvien_detail', [
            'staff' => $staff
        ]);
    }

    public function apiList() {
        $query = "SELECT * FROM staff ORDER BY staff_id ASC";
        $stmt = $this->db->query($query);
        $staffList = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $staffList]);
        exit;
    }

    public function apiDelete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if (isset($data['id']) && is_string($data['id'])) {
                $id = $data['id'];

                try {
                    $stmt = $this->db->prepare("DELETE FROM staff WHERE staff_id = :id");
                    $stmt->bindParam(':id', $id, PDO::PARAM_STR); // Changed to PDO::PARAM_STR
                    $stmt->execute();

                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Staff member deleted successfully.']);
                } catch (Exception $e) {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Error deleting staff member: ' . $e->getMessage()]);
                }
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid staff ID.']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
        }
        exit;
    }
}
?>

