<?php
require_once __DIR__ . '/../models/MenuModel.php';

class MenuController extends BaseController {
    private $menuModel;

    public function __construct() {
        parent::__construct();
        $this->menuModel = new MenuModel($this->db);
    }

    public function index() {
        // Get search term if any
        $searchTerm = $_GET['search'] ?? '';
        
        // Modify query to include search
        if (!empty($searchTerm)) {
            $query = "SELECT * FROM menu WHERE name LIKE :searchTerm ORDER BY id ASC";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':searchTerm', "%$searchTerm%");
        } else {
            $query = "SELECT * FROM menu ORDER BY id ASC";
            $stmt = $this->db->prepare($query);
        }
        
        $stmt->execute();
        $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $userRole = $_SESSION['user_role'] ?? '';
        $staffId = $_SESSION['staff_id'] ?? '';
        $username = $_SESSION['username'] ?? '';
        
        // Get staff name if staff is logged in
        $staffName = '';
        if ($userRole === 'staff' && !empty($staffId)) {
            $query = "SELECT name FROM staff WHERE staff_id = :staff_id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':staff_id', $staffId);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $staff = $stmt->fetch(PDO::FETCH_ASSOC);
                $staffName = $staff['name'];
            }
        }
        
        $this->render('danhsach', [
            'menuItems' => $menuItems,
            'userRole' => $userRole,
            'staffId' => $staffId,
            'username' => $username,
            'staffName' => $staffName
        ]);
    }
    
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image = $this->menuModel->handleImageUpload($_FILES['image']);
            }

            $data = [
                'name' => $_POST['name'] ?? '',
                'price' => $_POST['price'] ?? 0,
                'description' => $_POST['description'] ?? '',
                'image' => $image
            ];

            try {
                if ($this->menuModel->addItem($data)) {
                    $this->redirect('/danhsach');
                } else {
                    echo "Error adding menu item";
                }
            } catch (PDOException $e) {
                echo "Error adding menu item: " . $e->getMessage();
            }
        } else {
            $this->redirect('/danhsach');
        }
    }
    
    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            
            $data = [
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? '',
                'price' => $_POST['price'] ?? 0,
                'category' => $_POST['category'] ?? '',
                'image' => ''
            ];

            // Only process image if a new one is uploaded
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $data['image'] = $this->menuModel->handleImageUpload($_FILES['image']);
            }

            try {
                if ($this->menuModel->updateItem($id, $data)) {  // Changed from updateMenuItem to updateItem
                    $_SESSION['success'] = 'Cập nhật món thành công';
                } else {
                    $_SESSION['error'] = 'Không thể cập nhật món';
                }
            } catch (Exception $e) {
                $_SESSION['error'] = 'Lỗi khi cập nhật món: ' . $e->getMessage();
            }
            
            $this->redirect('/danhsach');
        } else {
            // GET request: Fetch item details and render the edit view
            $id = $_GET['id'] ?? 0;
            if ($id <= 0) {
                $this->redirect('/danhsach'); // Redirect if no ID
                return;
            }

            $query = "SELECT * FROM menu WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Explicitly bind as integer
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $menuItem = $stmt->fetch(PDO::FETCH_ASSOC);
                // Render the new edit view
                $this->render('menu_edit', ['menuItem' => $menuItem]);
            } else {
                // Item not found, redirect back to the list
                 $this->redirect('/danhsach');
            }
            // No exit needed here
        }
    }
    
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = $_POST['id'];
            
            try {
                // Fetch the product to get the image path
                $query = "SELECT image FROM menu WHERE id = :id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $menuItem = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($menuItem && !empty($menuItem['image'])) {
                    $imagePath = __DIR__ . '/../../uploads/menu/' . $menuItem['image'];
                    if (file_exists($imagePath)) {
                        unlink($imagePath); // Delete the image file
                    }
                }

                if ($this->menuModel->deleteItem($id)) {
                    $_SESSION['success'] = 'Xóa món thành công';
                } else {
                    $_SESSION['error'] = 'Không thể xóa món';
                }
            } catch (PDOException $e) {
                $_SESSION['error'] = 'Lỗi khi xóa món: ' . $e->getMessage();
            }
            
            $this->redirect('/danhsach');
        } else {
            $_SESSION['error'] = 'Yêu cầu không hợp lệ';
            $this->redirect('/danhsach');
        }
    }

    public function search() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $searchTerm = $_GET['term'] ?? '';
            
            $query = "SELECT * FROM menu WHERE name LIKE :searchTerm ORDER BY id ASC";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%');
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            header('Content-Type: application/json');
            echo json_encode($results);
            exit;
        }
    }

    public function apiList() {
        $query = "SELECT * FROM menu ORDER BY id ASC";
        $stmt = $this->db->query($query);
        $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $menuItems]);
        exit;
    }

    public function apiDelete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if (isset($data['id']) && is_numeric($data['id'])) {
                $id = (int)$data['id'];

                try {
                    $stmt = $this->db->prepare("DELETE FROM menu WHERE id = :id");
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();

                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Menu item deleted successfully.']);
                } catch (Exception $e) {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Error deleting menu item: ' . $e->getMessage()]);
                }
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid menu item ID.']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
        }
        exit;
    }
}
?>

