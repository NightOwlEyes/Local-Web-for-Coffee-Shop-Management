<?php
class OrderModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function calculateCartTotal($cart) {
        $total = 0;
        $items = [];
        
        if (empty($cart)) {
            return ['total' => 0, 'items' => []];
        }

        $itemIds = array_keys($cart);
        $placeholders = str_repeat('?,', count($itemIds) - 1) . '?';
        $menuQuery = "SELECT id, name, price FROM menu WHERE id IN ($placeholders)";
        $menuStmt = $this->db->prepare($menuQuery);
        $menuStmt->execute($itemIds);
        $menuItems = $menuStmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($menuItems as $item) {
            if (isset($cart[$item['id']])) {
                $quantity = $cart[$item['id']]['quantity'];
                $subtotal = $item['price'] * $quantity;
                $total += $subtotal;
                $items[] = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $quantity,
                    'subtotal' => $subtotal
                ];
            }
        }

        return ['total' => $total, 'items' => $items];
    }

    public function createOrder($data) {
        $this->db->beginTransaction();
        try {
            // Insert order
            $orderQuery = "INSERT INTO orders (order_id, staff_id, total_amount, payment_method, cash_received, cash_returned, order_date) 
                          VALUES (?, ?, ?, ?, ?, ?, NOW())";
            $orderStmt = $this->db->prepare($orderQuery);
            $cashReturned = ($data['payment_method'] === 'cash') ? ($data['cash_received'] - $data['total_amount']) : 0;
            
            $orderStmt->execute([
                $data['bill_number'],
                $data['staff_id'],
                $data['total_amount'],
                $data['payment_method'],
                $data['cash_received'],
                $cashReturned
            ]);
            
            $orderId = $data['bill_number']; // Use bill_number as order_id for order_items

            // Insert order items
            $itemQuery = "INSERT INTO order_items (order_id, menu_id, quantity, price, subtotal) 
                         VALUES (?, ?, ?, ?, ?)";
            $itemStmt = $this->db->prepare($itemQuery);
            
            foreach ($data['items'] as $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $itemStmt->execute([
                    $orderId,
                    $item['id'],
                    $item['quantity'],
                    $item['price'],
                    $subtotal
                ]);
            }

            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getOrderDetails($orderId) {
        $query = "SELECT o.*, s.name as staff_name 
                 FROM orders o 
                 LEFT JOIN staff s ON o.staff_id = s.staff_id 
                 WHERE o.id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrderByOrderId($orderId) {
        $query = "SELECT * FROM orders WHERE order_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrderItems($orderId) {
        $query = "SELECT oi.*, m.name as item_name 
                 FROM order_items oi 
                 JOIN menu m ON oi.menu_id = m.id 
                 WHERE oi.order_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$orderId]); 
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function generateBillNumber() {
        $prefix = 'HD';
        $query = "SELECT MAX(CAST(SUBSTRING(order_id, 3) AS UNSIGNED)) as max_num 
                 FROM orders 
                 WHERE order_id LIKE ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$prefix . '%']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $nextNum = ($result['max_num'] ?? 0) + 1;
        return $prefix . str_pad($nextNum, 4, '0', STR_PAD_LEFT); // Format as HD0001, HD0002, etc.
    }
}