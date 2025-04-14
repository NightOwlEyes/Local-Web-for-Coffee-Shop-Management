<?php
class StaffModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllStaff() {
        $query = "SELECT * FROM staff ORDER BY id ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStaffById($id) {
        $query = "SELECT s.*, u.username 
                 FROM staff s 
                 LEFT JOIN users u ON s.staff_id = u.staff_id 
                 WHERE s.id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStaffByStaffId($staffId) {
        $query = "SELECT * FROM staff WHERE staff_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$staffId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addStaff($data) {
        $this->db->beginTransaction();
        try {
            // Insert staff record
            $queryStaff = "INSERT INTO staff (staff_id, name, dob, phone, email, id_card, image, hire_date)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtStaff = $this->db->prepare($queryStaff);
            $stmtStaff->execute([
                $data['staff_id'],
                $data['name'],
                $data['dob'],
                $data['phone'],
                $data['email'],
                $data['id_card'],
                $data['image'],
                $data['hire_date']
            ]);

            // Create user account
            $queryUser = "INSERT INTO users (username, password, staff_id, role)
                         VALUES (?, ?, ?, 'staff')";
            $stmtUser = $this->db->prepare($queryUser);
            $stmtUser->execute([
                $data['username'],
                $data['password'],
                $data['staff_id']
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function updateStaff($id, $data) {
        try {
            $this->db->beginTransaction();
            
            // Get current staff to check existing image
            $currentStaff = $this->getStaffById($id);
            
            // If no new image uploaded, keep existing image
            if (empty($data['image'])) {
                $data['image'] = $currentStaff['image'];
            }

            // Update staff table
            $query = "UPDATE staff SET 
                     name = ?, 
                     dob = ?, 
                     phone = ?, 
                     email = ?, 
                     id_card = ?, 
                     hire_date = ?, 
                     image = ? 
                     WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $data['name'],
                $data['dob'],
                $data['phone'],
                $data['email'],
                $data['id_card'],
                $data['hire_date'],
                $data['image'],
                $id
            ]);

            // Update password if provided
            if (!empty($data['password'])) {
                $query = "UPDATE users SET password = ? WHERE staff_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    password_hash($data['password'], PASSWORD_DEFAULT),
                    $currentStaff['staff_id']
                ]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function deleteStaff($id) {
        $this->db->beginTransaction();
        try {
            // Get staff info before deleting
            $staff = $this->getStaffById($id);
            if (!$staff) {
                throw new Exception('Không tìm thấy nhân viên');
            }

            // Delete from users table first (foreign key relationship)
            $queryUser = "DELETE FROM users WHERE staff_id = ?";
            $stmtUser = $this->db->prepare($queryUser);
            $stmtUser->execute([$staff['staff_id']]);

            // Then delete from staff table
            $queryStaff = "DELETE FROM staff WHERE id = ?";
            $stmtStaff = $this->db->prepare($queryStaff);
            $stmtStaff->execute([$id]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function handleImageUpload($file) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/staff/';
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

    public function generateStaffId() {
        $prefix = 'NV';
        $query = "SELECT MAX(CAST(SUBSTRING(staff_id, 3) AS UNSIGNED)) as max_num FROM staff";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $nextNum = ($result['max_num'] ?? 0) + 1;
        return $prefix . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
    }

    public function generateUsername($name, $staffId) {
        $baseUsername = strtolower(str_replace(' ', '', preg_replace('/[^A-Za-z0-9 ]/', '', $name))) . substr($staffId, -2);
        $username = $baseUsername;
        $suffix = 1;

        $query = "SELECT 1 FROM users WHERE username = ? LIMIT 1";
        $stmt = $this->db->prepare($query);
        
        while (true) {
            $stmt->execute([$username]);
            if ($stmt->rowCount() === 0) {
                break;
            }
            $username = $baseUsername . $suffix;
            $suffix++;
        }

        return $username;
    }
}