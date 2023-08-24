<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electrak</title>
    <link rel="stylesheet" href="css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<style>
    .error-message {
        color: red;
        font-size: 16px;
        margin-bottom: 10px;
        border: 1px solid red;
        background-color: #f2dede;
        padding: 10px;
        border-radius: 5px;
    }
</style>

<body>

    <div class="bg-colour">

        <!-- logo -->
        <img src="images/logo.png" class="logo" alt="">


        <div class="container1">
            <h1 class="login_head">Login</h1>

            <?php
            if (isset($_GET['error']) && $_GET['error'] == 1) {
                echo '<div class="error-message">Incorrect username or password.</div>';
            }
            ?>

            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                    <div id="emailHelp" class="form-text">We'll never share your Username with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="forgot_password">
                    <a href="forgotPassword.php">Forgot Your Password?</a>
                </div>
                <button type="submit" class="login_btn">Login</button>
            </form>
        </div>
    </div>

    <script src="login.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>