<?php
class MenuModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllItems($searchTerm = '') {
        if (!empty($searchTerm)) {
            $query = "SELECT * FROM menu WHERE name LIKE :searchTerm ORDER BY id ASC";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':searchTerm', "%$searchTerm%");
        } else {
            $query = "SELECT * FROM menu ORDER BY id ASC";
            $stmt = $this->db->prepare($query);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItemById($id) {
        $query = "SELECT * FROM menu WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addItem($data) {
        $query = "INSERT INTO menu (name, price, description, image) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['name'],
            $data['price'],
            $data['description'],
            $data['image']
        ]);
    }

    public function updateItem($id, $data) {
        try {
            // Get current menu item to check existing image
            $currentItem = $this->getItemById($id);
            
            // If no new image uploaded, keep existing image
            if (empty($data['image'])) {
                $data['image'] = $currentItem['image'];
            }

            $query = "UPDATE menu SET name = ?, price = ?, description = ?, image = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                $data['name'],
                $data['price'],
                $data['description'],
                $data['image'],
                $id
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function deleteItem($id) {
        $query = "DELETE FROM menu WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }

    public function handleImageUpload($file) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/menu/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = basename($file['name']);
            $uploadFile = $uploadDir . time() . '_' . $fileName;
            
            if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                return basename($uploadFile);
            }
        }
        return '';
    }
}