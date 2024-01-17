<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/public/css/main.css">
    <link rel="stylesheet" href="/public/css/login.css">
    <link rel="stylesheet" href="/public//css//form-controls.css">

    <script src="/public/js/stripURL.js"></script>
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
        <form class="LoginForm" method="POST" action="login">
            <label for="emailInput">Email</label>
            <input id="emailInput" name="username" type="email">

            <label for="passwordInput">Password</label>
            <input id="passwordInput" name="password" type="password">

            <label class="checkbox">
                Remember me
                <input type="checkbox" name="rememberMe">
                <span class="checkmark"></span>

            </label>

            <button type="submit">Login</button>

            <h5>Don't have an account yet? <a href="/register">Register now</a></h5>
        </form>
    </div>
</body>
</html>