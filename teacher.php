<?php
session_start();

// Redirect users who are not logged in or are not admins
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: unauthorized.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "user_registrations";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql) !== TRUE) {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($database);

// Create table if it doesn't exist
$tableSql = "
CREATE TABLE IF NOT EXISTS student_grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    surname VARCHAR(50) NOT NULL,
    course INT NOT NULL,
    faculty VARCHAR(50) NOT NULL DEFAULT 'Natural Sciences',
    major VARCHAR(50) NOT NULL,
    grade DECIMAL(5,2) NOT NULL
)";
if ($conn->query($tableSql) !== TRUE) {
    die("Error creating table: " . $conn->error);
}

// Insert data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $course = $_POST['course'];
    $faculty = "Բնական գիտություններ";
    $major = $_POST['major'];
    $grade = $_POST['grade'];

    $insertSql = "INSERT INTO student_grades (name, surname, course, faculty, major, grade) 
                  VALUES ('$name', '$surname', '$course', '$faculty', '$major', '$grade')";

    if ($conn->query($insertSql) !== TRUE) {
        echo "Error: " . $insertSql . "<br>" . $conn->error;
    }
}

// Update data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $course = $_POST['course'];
    $major = $_POST['major'];
    $grade = $_POST['grade'];

    $updateSql = "UPDATE student_grades 
                  SET name='$name', surname='$surname', course='$course', major='$major', grade='$grade' 
                  WHERE id=$id";

    if ($conn->query($updateSql) === TRUE) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Delete data
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteSql = "DELETE FROM student_grades WHERE id=$id";

    if ($conn->query($deleteSql) !== TRUE) {
        echo "Error deleting record: " . $conn->error;
    }
}

// Fetch data
$selectSql = "SELECT * FROM student_grades ORDER BY id DESC";
$result = $conn->query($selectSql);
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Դասախոսի անձնական էջ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #540089, #5d008f 25%, #540089 50%, #5d008f 75%, #540089);
            margin: 0;
            padding: 20px;
        }

        header {
            position: relative;
            height: 500px;
            background-image: url('./image/imagess.png'), linear-gradient(to bottom, #540089, #5d008f 25%, #540089 50%, #5d008f 75%, #540089);
            background-position: center;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            width: 100%;
            height: 100px;
        }

        h2 {
            color: white;
            text-align: center;
        }

        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
<header></header>
<div class="form-container">
    <form method="POST" action="">
        <label for="name">Անուն:</label>
        <input type="text" id="name" name="name" required>

        <label for="surname">Ազգանուն:</label>
        <input type="text" id="surname" name="surname" required>

        <label for="course">Կուրս:</label>
        <select id="course" name="course" required>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select>

        <label for="major">Առարկա:</label>
        <input type="text" id="major" name="major" required>

        <label for="grade">Գնահատական (0-20):</label>
        <input type="number" id="grade" name="grade" min="0" max="20" required>

        <input type="submit" name="add" value="Ավելացնել">
    </form>
</div>

<h2>Ուսանողների արդյուքնները</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Անուն</th>
        <th>Ազգանուն</th>
        <th>Կուրս</th>
        <th>Ֆակուլտետ</th>
        <th>Առարկա</th>
        <th>Գնահատական</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['surname']; ?></td>
                <td><?php echo $row['course']; ?></td>
                <td><?php echo $row['faculty']; ?></td>
                <td><?php echo $row['major']; ?></td>
                <td><?php echo $row['grade']; ?></td>
                <td>
                   <a href="?edit=<?php echo $row['id']; ?>">Խմբագրել</a>
                   <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?')">Ջնջել</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="8">No records found.</td></tr>
    <?php endif; ?>
</table>

<?php if (isset($_GET['edit'])): 
    $editId = $_GET['edit'];
    $conn = new mysqli($servername, $username, $password, $database);
    $editQuery = "SELECT * FROM student_grades WHERE id=$editId";
    $editResult = $conn->query($editQuery);
    $editRow = $editResult->fetch_assoc();      
	
?>


<div class="form-container">
    <form method="POST" action="">
        <h2>Խմբագրել ուսանողի տվյալները</h2>
        <input type="hidden" name="id" value="<?php echo $editRow['id']; ?>">
        <label for="name">Անուն:</label>
        <input type="text" id="name" name="name" value="<?php echo $editRow['name']; ?>" required>

        <label for="surname">Ազգանուն:</label>
        <input type="text" id="surname" name="surname" value="<?php echo $editRow['surname']; ?>" required>

        <label for="course">Կուրս:</label>
        <select id="course" name="course" required>
            <option value="1" <?php if ($editRow['course'] == 1) echo "selected"; ?>>1</option>
            <option value="2" <?php if ($editRow['course'] == 2) echo "selected"; ?>>2</option>
            <option value="3" <?php if ($editRow['course'] == 3) echo "selected"; ?>>3</option>
            <option value="4" <?php if ($editRow['course'] == 4) echo "selected"; ?>>4</option>
        </select>

        <label for="major">Առարկա:</label>
        <input type="text" id="major" name="major" value="<?php echo $editRow['major']; ?>" required>

        <label for="grade">Գնահատական (0-20):</label>
        <input type="number" id="grade" name="grade" min="0" max="20" value="<?php echo $editRow['grade']; ?>" required>

        <input type="submit" name="update" value="Update">
		<?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ubdate'])) {
                header("Location: teacher.php");
                     exit();}
            ?>

    </form>
</div>
<?php endif; ?>

</body>
</html>