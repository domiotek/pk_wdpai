<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/public/css/main.css">
    <link rel="stylesheet" href="/public/css/login.css">
    <link rel="stylesheet" href="/public//css//form-controls.css">

    <title>Taskmate</title>

    <link rel="icon" href="/public/img/logo.png" />
</head>
<body>
    <div class="ContentWrapper">
        <img src="/public/img/logo.png" alt="Logo">
        <p>
            <?php 
                if(isset($message)) {
                    echo $message;
                }
            ?>
        </p>
        <form class="RegisterForm" method="POST" action="register">
            <label for="emailInput">Email</label>
            <input id="emailInput" name="username" type="email" autocomplete="username" required value="<?php if(isset($email)) echo $email?>">

            <label for="nameInput">Name</label>
            <input id="nameInput" name="name" type="text" required value="<?php if(isset($name)) echo $name?>">

            <label for="passwordInput">Password</label>
            <input id="passwordInput" name="password" type="password" autocomplete="new-password" required>
            
            <label for="cpasswordInput">Confirm Password</label>
            <input id="cpasswordInput" type="password" name = "cpassword" autocomplete="confirm-password" required>

            <button type="submit">Register</button>

            <h5>Already have an account? <a href="/login">Login now</a></h5>
        </form>
    </div>
</body>
</html>