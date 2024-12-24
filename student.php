<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "user_registrations";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql) !== TRUE) {
    die("Error creating database: " . $conn->error);
}

$conn->select_db($database);

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $course = $_POST['course'];
    $faculty = "Natural Sciences";
    $major = $_POST['major'];
    $grade = $_POST['grade'];

    $insertSql = "INSERT INTO student_grades (name, surname, course, faculty, major, grade)
                  VALUES ('$name', '$surname', '$course', '$faculty', '$major', '$grade')";

    if ($conn->query($insertSql) !== TRUE) {
        echo "Error: " . $insertSql . "<br>" . $conn->error;
    }
}

$selectSql = "SELECT * FROM student_grades ORDER BY id DESC";
$result = $conn->query($selectSql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ուսանող</title>
	<link rel="stylesheet" href="./style/student.css">
    <style>
		header{
			background-image: url('././image/imagess.png'), linear-gradient(to bottom, 
                #540089, 
                #5d008f 25%, 
                #540089 50%, 
                #5d008f 75%, 
                #540089); 
		}
    </style>
</head>
<body>
<header></header>

<h2>Գնահատականների արդյունքներ</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Անուն</th>
        <th>Ազգանուն</th>
        <th>Կուրս</th>
        <th>Ֆակուլտետ</th>
        <th>Մասնագիտություն</th>
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
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="7">No records found.</td></tr>
    <?php endif; ?>
</table>

</body>
</html>
