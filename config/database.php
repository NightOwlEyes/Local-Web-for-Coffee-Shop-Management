<?php
class Database {
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $conn;
    
    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }
        
        return $this->conn;
    }
    
    public function createTables() {
        $conn = $this->getConnection();
        
        // Create users table
        $query = "CREATE TABLE IF NOT EXISTS users (
            id INT(11) NOT NULL AUTO_INCREMENT,
            username VARCHAR(50) NOT NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'staff') NOT NULL,
            staff_id VARCHAR(10) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY (username)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $conn->exec($query);
        
        // Create staff table
        $query = "CREATE TABLE IF NOT EXISTS staff (
            id INT(11) NOT NULL AUTO_INCREMENT,
            staff_id VARCHAR(10) NOT NULL,
            name VARCHAR(100) NOT NULL,
            dob DATE NOT NULL,
            phone VARCHAR(15) NOT NULL,
            email VARCHAR(100) NOT NULL,
            id_card VARCHAR(20) NOT NULL,
            image VARCHAR(255) NULL,
            hire_date DATE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY (staff_id),
            UNIQUE KEY (id_card)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $conn->exec($query);
        
        // Create menu table
        $query = "CREATE TABLE IF NOT EXISTS menu (
            id INT(11) NOT NULL AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            description TEXT NULL,
            image VARCHAR(255) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $conn->exec($query);
        
        // Create orders table
        $query = "CREATE TABLE IF NOT EXISTS orders (
            id INT(11) NOT NULL AUTO_INCREMENT,
            order_id VARCHAR(10) NOT NULL,
            staff_id VARCHAR(10) NOT NULL,
            total_amount DECIMAL(10,2) NOT NULL,
            payment_method ENUM('cash', 'transfer') NOT NULL,
            cash_received DECIMAL(10,2) NULL,
            cash_returned DECIMAL(10,2) NULL,
            order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY (order_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $conn->exec($query);
        
        // Create order_items table
        $query = "CREATE TABLE IF NOT EXISTS order_items (
            id INT(11) NOT NULL AUTO_INCREMENT,
            order_id VARCHAR(10) NOT NULL,
            menu_id INT(11) NOT NULL,
            quantity INT(11) NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            subtotal DECIMAL(10,2) NOT NULL,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $conn->exec($query);
        
        // Insert default admin user
        $query = "INSERT IGNORE INTO users (username, password, role) VALUES ('admin', :password, 'admin')";
        $stmt = $conn->prepare($query);
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        
        // Check if menu table is empty before inserting sample items
        $checkMenu = "SELECT COUNT(*) FROM menu";
        $count = $conn->query($checkMenu)->fetchColumn();
        
        if ($count == 0) {
            // Insert sample menu items only if table is empty
            $menuItems = [
                ['Cà phê latte đá xay', 20000, 'Cà phê latte đá xay thơm ngon', 'latte.jpg'],
                ['Bánh flan', 5000, 'Bánh flan mềm mịn', 'flan.jpg'],
                ['Trà sữa trân châu', 25000, 'Trà sữa trân châu đường đen', 'trasua.jpg'],
                ['Bánh mì thịt', 15000, 'Bánh mì thịt đặc biệt', 'banhmi.jpg']
            ];
            
            $query = "INSERT INTO menu (name, price, description, image) VALUES (:name, :price, :description, :image)";
            $stmt = $conn->prepare($query);
            
            foreach ($menuItems as $item) {
                $stmt->bindParam(':name', $item[0]);
                $stmt->bindParam(':price', $item[1]);
                $stmt->bindParam(':description', $item[2]);
                $stmt->bindParam(':image', $item[3]);
                $stmt->execute();
            }
        }
    }
}

// Create tables if they don't exist
$database = new Database();
$database->createTables();
?>

