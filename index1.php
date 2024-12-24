<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ԵՊՀ ԻՄ Գնահատման համակարգ</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <div class="animation-container">
        <h1 class="grading-system">ԵՊՀ ԻՄ ԳՆԱՀԱՏՄԱՆ ՀԱՄԱԿԱՐԳ</h1>
        <h2 class="student-registration">ԵՊՀ ԻՄ ուսանողների գրանցում</h2>
    </div>

    <div class="bigdiv">
        <div class="form">
            <form action="request.php" method="post" enctype="multipart/form-data">
                <label for="name">Անուն:</label>
                <input type="text" id="name" name="name"><br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email"><br><br>

                <label for="password">Գաղտնաբառ:</label>
                <input type="password" id="password" name="password"><br><br>

                <label for="phone">Հեռ:</label>
                <input type="text" id="phone" name="phone"><br><br>

                <label for="address">Հասցե:</label>
                <input type="text" id="address" name="address"><br><br>

                <label for="role">Կարգավիճակ:</label>
                <select id="role" name="role">
                    <option value="user">Ուսանող</option>
                </select><br><br>

                <label for="profile_picture">Ներբեռնեք լուսանկարը:</label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*"><br><br>

                <button type="submit" class="register-btn">Գրանցում</button>
            </form>
            <br><br>
            <a href="loginhtml.php"><button class="login-btn">Արդեն գրանցված եք? Մուտք</button></a>
        </div>
    </div>
</body>
</html>
