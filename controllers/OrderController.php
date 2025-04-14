<?php
require_once __DIR__ . '/../models/OrderModel.php';

class OrderController extends BaseController {
    private $orderModel;

    public function __construct() {
        parent::__construct();
        $this->orderModel = new OrderModel($this->db);
    }

    public function checkout() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $cart = $_SESSION['cart'];
        $result = $this->orderModel->calculateCartTotal($cart);
        
        if (empty($result['items'])) {
            $_SESSION['flash_message'] = ['type' => 'warning', 'text' => 'Giỏ hàng trống sau khi kiểm tra.'];
            $this->redirect('/danhsach');
            return;
        }

        $this->render('thanhtoan', [
            'menuItems' => $result['items'],
            'totalAmount' => $result['total'],
            'appName' => APP_NAME,
            'cafePhone' => CAFE_PHONE,
            'cafeEmail' => CAFE_EMAIL,
            'staffId' => $_SESSION['staff_id'] ?? 'N/A'
        ]);
    }

    public function process() {
        try {
            if (!isset($_SESSION['user_id'])) {
                throw new Exception('Vui lòng đăng nhập');
            }

            $paymentMethod = $_POST['payment_method'] ?? '';
            if (!in_array($paymentMethod, ['cash', 'transfer'])) {
                throw new Exception('Phương thức thanh toán không hợp lệ');
            }

            $cashReceived = ($paymentMethod === 'cash') ? floatval($_POST['cash_received'] ?? 0) : 0;
            $staffId = $_SESSION['staff_id'] ?? null;

            if (empty($staffId)) {
                throw new Exception('Không tìm thấy thông tin nhân viên');
            }

            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                throw new Exception('Giỏ hàng trống');
            }

            $result = $this->orderModel->calculateCartTotal($_SESSION['cart']);
            if ($paymentMethod === 'cash' && $cashReceived < $result['total']) {
                throw new Exception('Số tiền khách đưa không đủ');
            }

            $billNumber = $this->orderModel->generateBillNumber();

            $orderId = $this->orderModel->createOrder([
                'bill_number' => $billNumber,
                'staff_id' => $staffId,
                'total_amount' => $result['total'],
                'payment_method' => $paymentMethod,
                'cash_received' => $cashReceived,
                'items' => $result['items']
            ]);

            $_SESSION['order_data'] = [
                'bill_number' => $billNumber,
                'items' => $result['items'],
                'total_amount' => $result['total'],
                'payment_method' => $paymentMethod,
                'cash_received' => $cashReceived,
                'cash_returned' => ($paymentMethod === 'cash') ? ($cashReceived - $result['total']) : 0,
                'staff_id' => $staffId,
                'created_at' => date('Y-m-d H:i:s')
            ];

            unset($_SESSION['cart']);

            echo json_encode([
                'success' => true,
                'redirect' => BASE_URL . '/hoadon'
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    public function hoadon() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        if (isset($_GET['id'])) {
            $orderId = $_GET['id'];
            $order = $this->orderModel->getOrderByOrderId($orderId);
            
            if (!$order) {
                $_SESSION['error'] = 'Không tìm thấy hóa đơn';
                $this->redirect('/');
                return;
            }

            $orderItems = $this->orderModel->getOrderItems($orderId); // Use order_id instead of id
            $orderData = [
                'bill_number' => $order['order_id'],
                'items' => array_map(function($item) {
                    return [
                        'name' => $item['item_name'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['subtotal']
                    ];
                }, $orderItems),
                'total_amount' => $order['total_amount'],
                'payment_method' => $order['payment_method'],
                'cash_received' => $order['cash_received'],
                'cash_returned' => $order['cash_returned'],
                'created_at' => $order['order_date'],
                'staff_id' => $order['staff_id']
            ];
        } elseif (isset($_SESSION['order_data'])) {
            $orderData = $_SESSION['order_data'];
            unset($_SESSION['order_data']);
        } else {
            $_SESSION['error'] = 'Không tìm thấy thông tin hóa đơn';
            $this->redirect('/');
            return;
        }

        $this->render('hoadon', [
            'orderData' => $orderData,
            'cafePhone' => CAFE_PHONE,
            'cafeEmail' => CAFE_EMAIL
        ]);
    }

    public function addToCartBulk() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = []; // Initialize cart if not exists
            }

            if (isset($data['cart']) && is_array($data['cart'])) {
                $messages = [];
                $success = true;

                foreach ($data['cart'] as $item) {
                    if (isset($item['id'], $item['quantity']) &&
                        is_numeric($item['id']) &&
                        is_numeric($item['quantity'])) {

                        $itemId = (int)$item['id'];
                        $quantity = (int)$item['quantity'];

                        if ($quantity > 0) {
                            // Add or update item quantity
                            // Store as associative array with id as key for easy access
                            $_SESSION['cart'][$itemId] = ['quantity' => $quantity];
                            // Optionally add a success message per item if needed
                        } elseif ($quantity <= 0 && isset($_SESSION['cart'][$itemId])) {
                            // Remove item if quantity is 0 or less
                            unset($_SESSION['cart'][$itemId]);
                            $messages[] = "Item ID $itemId removed.";
                        } else {
                             // Invalid quantity for a new item
                             $messages[] = "Invalid quantity for item ID $itemId.";
                             $success = false; // Mark as overall failure if any item is invalid
                        }
                    } else {
                        $messages[] = 'Invalid item data received.';
                        $success = false;
                    }
                }

                header('Content-Type: application/json');
                if ($success) {
                    echo json_encode(['success' => true, 'message' => 'Cart updated successfully.']);
                } else {
                    http_response_code(400); // Bad Request
                    echo json_encode(['success' => false, 'message' => implode(' ', $messages)]);
                }

            } else {
                header('Content-Type: application/json');
                http_response_code(400); // Bad Request
                echo json_encode(['success' => false, 'message' => 'Invalid or missing cart data received']);
            }
        } else {
             http_response_code(405); // Method Not Allowed
             echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
        }
         exit;
    }

    public function getCartCount() {
        $count = 0;
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            // Sum quantities from the associative array
            foreach ($_SESSION['cart'] as $itemData) {
                $count += (int)$itemData['quantity'];
            }
        }
        header('Content-Type: application/json');
        echo json_encode(['count' => $count]);
        exit;
    }

    // Remove old cart methods if they are no longer used anywhere else
    // public function addToCart() { ... }
    // public function updateCart() { ... }
    // public function removeFromCart() { ... }

    // Override generateRandomId to ensure length matches DB schema if needed
    protected function generateRandomId($prefix, $length = 8) { // Default to 8 based on usage in process()
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // Added letters for more combinations
        $randomString = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $length; $i++) {
            $randomString .= $characters[random_int(0, $max)]; // Use random_int for better randomness
        }
        return $prefix . $randomString;
    }

    public function generateBillNumber() {
        $stmt = $this->db->query("SELECT MAX(CAST(SUBSTRING(order_id, 3) AS UNSIGNED)) as max_num FROM orders");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $nextNum = ($result['max_num'] ?? 0) + 1;
        return 'HD' . str_pad($nextNum, 2, '0', STR_PAD_LEFT);
    }

    public function setupDatabase() {
        try {
            $sql = file_get_contents(__DIR__ . '/../database/init.sql');
            $this->db->exec($sql);
            echo "Tables created successfully";
        } catch (PDOException $e) {
            echo "Error creating tables: " . $e->getMessage();
        }
    }

    public function view() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        $orderId = $_GET['id'] ?? '';
        
        if (empty($orderId)) {
            $this->redirect('/doanhthu');
            return;
        }
    
        $query = "SELECT o.*, o.order_id as bill_number, o.order_date as created_at, s.name as staff_name 
                  FROM orders o
                  LEFT JOIN staff s ON o.staff_id = s.staff_id 
                  WHERE o.order_id = :order_id";
                  
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_id', $orderId);
        $stmt->execute();
        
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            $_SESSION['error'] = 'Không tìm thấy đơn hàng';
            $this->redirect('/doanhthu');
            return;
        }
    
        // Get order items
        $itemsQuery = "SELECT oi.*, m.name 
                       FROM order_items oi
                       LEFT JOIN menu m ON oi.menu_id = m.id 
                       WHERE oi.order_id = :order_id";
                       
        $itemsStmt = $this->db->prepare($itemsQuery);
        $itemsStmt->bindParam(':order_id', $order['id']);
        $itemsStmt->execute();
        $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Format data to match expected structure
        $orderData = [
            'bill_number' => $order['bill_number'],
            'items' => array_map(function($item) {
                return [
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal']
                ];
            }, $items),
            'total_amount' => $order['total_amount'],
            'payment_method' => $order['payment_method'],
            'cash_received' => $order['cash_received'],
            'cash_returned' => $order['cash_returned'],
            'created_at' => $order['created_at'],
            'staff_id' => $order['staff_id']
        ];
    
        $this->render('hoadon', [
            'orderData' => $orderData,
            'cafePhone' => CAFE_PHONE,
            'cafeEmail' => CAFE_EMAIL
        ]);
    }

    public function apiList() {
        $query = "SELECT o.*, s.name as staff_name FROM orders o LEFT JOIN staff s ON o.staff_id = s.staff_id ORDER BY o.order_date DESC";
        $stmt = $this->db->query($query);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $orders]);
        exit;
    }

    public function apiDelete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if (isset($data['id']) && is_numeric($data['id'])) {
                $id = (int)$data['id'];

                try {
                    $stmt = $this->db->prepare("DELETE FROM orders WHERE order_id = :id");
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();

                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Order deleted successfully.']);
                } catch (Exception $e) {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Error deleting order: ' . $e->getMessage()]);
                }
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid order ID.']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
        }
        exit;
    }
}
?>

