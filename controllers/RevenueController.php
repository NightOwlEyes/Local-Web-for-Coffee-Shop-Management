<?php
require_once 'models/RevenueModel.php';

class RevenueController extends BaseController {
    private $revenueModel;

    public function __construct() {
        parent::__construct();
        $this->revenueModel = new RevenueModel($this->db);
    }

    public function index() {
        $this->render('doanhthu');
    }

    public function monthly() {
        $day = $_GET['day'] ?? '';
        $month = $_GET['month'] ?? '';
        $year = $_GET['year'] ?? date('Y');

        $data = $this->revenueModel->getRevenue($day, $month, $year);
        
        $this->render('doanhthu', [
            'revenue' => $data['revenue'],
            'orders' => $data['orders'],
            'selected' => [
                'day' => $day,
                'month' => $month,
                'year' => $year
            ]
        ]);
    }

    public function search() {
        $orderId = $_POST['order_id'] ?? '';
        if (!$orderId) {
            $_SESSION['error'] = 'Vui lòng nhập mã đơn hàng';
            $this->redirect('/doanhthu');
            return;
        }

        $orderData = $this->revenueModel->getOrderById($orderId);
        if (!$orderData) {
            $_SESSION['error'] = 'Không tìm thấy đơn hàng';
            $this->redirect('/doanhthu');
            return;
        }

        $this->render('doanhthu_detail', [
            'order' => $orderData,
            'orderItems' => $orderData['items']
        ]);
    }

    public function apiOverview() {
        $query = "SELECT 
                    COUNT(*) as total_orders, 
                    SUM(total_amount) as total_revenue, 
                    SUM(CASE WHEN payment_method = 'cash' THEN total_amount ELSE 0 END) as cash_revenue, 
                    SUM(CASE WHEN payment_method = 'transfer' THEN total_amount ELSE 0 END) as transfer_revenue 
                  FROM orders";
        $stmt = $this->db->query($query);
        $revenue = $stmt->fetch(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $revenue]);
        exit;
    }
}
?>

