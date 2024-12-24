<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Մուտք</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body class="login">
	<div class="animation-container" id="login-div">
			<h2 class="student-registration">ԵՊՀ ԻՄ ուսանողների մուտք</h2>
		</div>
    <div class="bigdiv" style="margin:50px 900px">
        <h2>Մուտք</h2>
		 <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
    <?php endif; ?>
        <form action="login.php" method="POST">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email">
            <label for="password">Գաղտնաբառ</label>
            <input type="password" id="password" name="password" placeholder="Enter your password">
            <button type="submit">Մուտք</button>
        </form>
		  <div style="text-align: center; margin-top: 15px;">
            <a href="index1.php" style="text-decoration: none;">
                <button type="button" > Գրանցվել </button>
            </a>
        </div>
		
    </div>
</body>
</html>
