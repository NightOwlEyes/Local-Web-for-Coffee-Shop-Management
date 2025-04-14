<?php
class RevenueModel {
    private $db;

    public function __construct($db) {
        if (!$db) {
            throw new Exception('Database connection is required');
        }
        $this->db = $db;
    }

    public function getRevenue($day = null, $month = null, $year = null) {
        $whereClause = "WHERE 1=1";
        $params = [];
        
        if (!empty($year)) {
            $whereClause .= " AND YEAR(order_date) = :year";
            $params[':year'] = $year;
            
            if (!empty($month)) {
                $whereClause .= " AND MONTH(order_date) = :month";
                $params[':month'] = $month;
                
                if (!empty($day)) {
                    $whereClause .= " AND DAY(order_date) = :day";
                    $params[':day'] = $day;
                }
            }
        }

        // Get revenue statistics
        $query = "SELECT 
                    COALESCE(COUNT(*), 0) as total_orders,
                    COALESCE(SUM(total_amount), 0) as total_revenue,
                    COALESCE(SUM(CASE WHEN payment_method = 'cash' THEN total_amount ELSE 0 END), 0) as cash_revenue,
                    COALESCE(SUM(CASE WHEN payment_method = 'transfer' THEN total_amount ELSE 0 END), 0) as transfer_revenue
                 FROM orders 
                 $whereClause";

        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $revenue = $stmt->fetch(PDO::FETCH_ASSOC);

        // Get orders list
        $query = "SELECT o.*, o.order_date, s.name as staff_name 
                 FROM orders o
                 LEFT JOIN staff s ON o.staff_id = s.staff_id 
                 $whereClause 
                 ORDER BY o.order_date DESC";

        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'revenue' => $revenue,
            'orders' => $orders
        ];
    }

    public function getOrderById($orderId) {
        $query = "SELECT o.*, s.name as staff_name 
                 FROM orders o
                 LEFT JOIN staff s ON o.staff_id = s.staff_id 
                 WHERE o.order_id = :order_id";
                 
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_id', $orderId);
        $stmt->execute();
        
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($order) {
            // Get order items
            $itemsQuery = "SELECT oi.*, m.name as menu_name 
                         FROM order_items oi
                         LEFT JOIN menu m ON oi.menu_id = m.id 
                         WHERE oi.order_id = :order_id";
                         
            $itemsStmt = $this->db->prepare($itemsQuery);
            $itemsStmt->bindParam(':order_id', $order['id']);
            $itemsStmt->execute();
            $order['items'] = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return $order;
    }
}
