<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electrak</title>
    <link rel="stylesheet" href="css/forgotPassword.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

</head>

<style>
    .success-message {
        color: green;
        font-size: 16px;
        margin-bottom: 10px;
        border: 1px solid green;
        background-color: lightgreen;
        padding: 10px;
        border-radius: 5px;
    }

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

        <img src="images/logo.png" class="logo" alt="">


        <div class="container1">


            <h1 class="login_head">Recover Your Password</h1>
            <div id="message"></div>

            <?php
            if (isset($_GET['status']) && $_GET['status'] == 1) {
                echo '<div class="success-message">Password Changed Successfull</div>';
            } else if (isset($_GET['status']) && $_GET['status'] == 0) {
                echo '<div class="error-message">Unable to change Password</div>';
            }
            ?>


            <form action="update_password.php" method="POST" id="updateForm">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" name="email" required>
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                </div>
                <button type="submit" class="login_btn">Change Password</button>
            </form>
            <button class="back_btn"><a href="loginPage.php">Back</a></button>

        </div>
    </div>


    <script src="forgotPassword.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>