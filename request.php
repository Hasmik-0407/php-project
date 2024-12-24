<?php
session_start();

class UserRegistration {
    private $servername = "localhost";
    private $username = "root";
    private $password_db = "";
    private $database = "user_registrations";
    private $conn;

    public function __construct() {
        $this->connectDatabase();
        $this->createDatabaseIfNeeded();
        $this->createTableIfNeeded();
        $this->addDefaultAdminUser();
    }

    private function connectDatabase() {
        $this->conn = mysqli_connect($this->servername, $this->username, $this->password_db);
        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    private function createDatabaseIfNeeded() {
        $sql = "CREATE DATABASE IF NOT EXISTS $this->database";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error creating database: " . mysqli_error($this->conn));
        }
        mysqli_select_db($this->conn, $this->database);
    }

    private function createTableIfNeeded() {
        $sql = "CREATE TABLE IF NOT EXISTS user_info (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(30) NOT NULL,
            email VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            phone VARCHAR(15) NOT NULL,
            address VARCHAR(100) NOT NULL,
            role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
            profile_picture VARCHAR(255) DEFAULT NULL
        )";
        if (!mysqli_query($this->conn, $sql)) {
            die("Error creating table: " . mysqli_error($this->conn));
        }
    }

    private function addDefaultAdminUser() {
        $defaultEmail = "admin@gmail.com";
        $defaultPassword = password_hash("admin", PASSWORD_BCRYPT);
        $checkAdmin = "SELECT * FROM user_info WHERE email = '$defaultEmail'";
        $result = mysqli_query($this->conn, $checkAdmin);

        if (mysqli_num_rows($result) === 0) {
            $addAdmin = "INSERT INTO user_info (name, email, password, phone, address, role) 
                         VALUES ('Admin', '$defaultEmail', '$defaultPassword', 'N/A', 'N/A', 'admin')";
            mysqli_query($this->conn, $addAdmin);
        }
    }

    public function isEmailUsed($email) {
        $result = mysqli_query($this->conn, "SELECT * FROM user_info WHERE email = '$email'");
        return mysqli_num_rows($result) > 0;
    }

    public function registerUser($name, $email, $password, $phone, $address, $role, $profilePicturePath) {
        if ($this->isEmailUsed($email) || $email === 'admin@gmail.com' || $password === 'admin') {
            $_SESSION['error'] = "Invalid email or password for registration!";
            return false;
        }

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $insert = "INSERT INTO user_info (name, email, password, phone, address, role, profile_picture) 
                   VALUES ('$name', '$email', '$hashed_password', '$phone', '$address', '$role', '$profilePicturePath')";

        if (mysqli_query($this->conn, $insert)) {
            $_SESSION['perfect'] = "Congratulations! You have successfully registered.";
            return true;
        } else {
            $_SESSION['error'] = "Error: Could not execute $insert. " . mysqli_error($this->conn);
            return false;
        }
    }

    public function closeConnection() {
        mysqli_close($this->conn);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $password = $_POST["password"];
    $role = $_POST["role"];
    $profilePicture = $_FILES["profile_picture"];

    if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($password) || empty($role) || empty($profilePicture["name"])) {
        echo "All fields are required!";
        exit;
    }

    $targetDir = "uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $targetFile = $targetDir . basename($profilePicture["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedTypes = ["jpg", "jpeg", "png", "gif"];

    if (!in_array($imageFileType, $allowedTypes)) {
        echo "Only JPG, JPEG, PNG, and GIF files are allowed.";
        exit;
    }

    if (move_uploaded_file($profilePicture["tmp_name"], $targetFile)) {
        $userReg = new UserRegistration();

        if ($userReg->registerUser($name, $email, $password, $phone, $address, $role, $targetFile)) {
            header("Location: loginhtml.php");
        } else {
            echo $_SESSION["error"];
        }

        $userReg->closeConnection();
    } else {
        echo "There was an error uploading the file.";
    }
}
?>
