<?php
class DatabaseSetup
{
    private $servername = "localhost";
    private $username = "root";
    private $password_db = ""; 
    private $database = "user_management";
    private $conn;

    public function __construct()
    {
        // Create a connection
        $this->conn = mysqli_connect($this->servername, $this->username, $this->password_db);
        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    public function createDatabase()
    {
        $query = "CREATE DATABASE IF NOT EXISTS $this->database";
        if (mysqli_query($this->conn, $query)) {
            echo "Database created successfully or already exists.<br>";
        } else {
            die("Error creating database: " . mysqli_error($this->conn));
        }

        // Select the database
        mysqli_select_db($this->conn, $this->database);
    }

    public function createTables()
    {
        // SQL for users table
        $usersTable = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL,
            email VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role_id TINYINT NOT NULL
        )";

        // SQL for publications table
        $publicationsTable = "CREATE TABLE IF NOT EXISTS publications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(100) NOT NULL,
            description TEXT NOT NULL,
            image VARCHAR(255) NOT NULL,
            created_by INT NOT NULL,
            FOREIGN KEY (created_by) REFERENCES users(id)
        )";

        // Execute queries
        if (mysqli_query($this->conn, $usersTable)) {
            echo "Users table created successfully or already exists.<br>";
        } else {
            die("Error creating users table: " . mysqli_error($this->conn));
        }

        if (mysqli_query($this->conn, $publicationsTable)) {
            echo "Publications table created successfully or already exists.<br>";
        } else {
            die("Error creating publications table: " . mysqli_error($this->conn));
        }
    }

    public function __destruct()
    {
        // Close the connection
        mysqli_close($this->conn);
    }
}

// Instantiate the DatabaseSetup class and create the database and tables
$setup = new DatabaseSetup();
$setup->createDatabase();
$setup->createTables();
?>
