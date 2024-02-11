<?php  
            require "functions.php";

            if (isset($_POST["submit"])) {
                $response = passwordReset($_POST["email"]);
            }

?>


<!DOCTYPE html>
<html lang="en">
    <head></head>

    <body>
        <form action="" method="post" autocomplete="off">
            <h2>Password reset</h2>
            <h4>Please enter your email so we can send you a new password</h4>
            <div>
                <label>Email</label>
                <input type="text" name="email" value="<?php echo @$_POST['email'];?>">
            </div>

            <button type="submit" name="submit">Submit</button>

            <p>
                <a href="login.php">Back to login page?</a>
            </p>

            <?php  
                    if (@$response == "success") {
                    ?>
                        <p class="success">Please go to your email account and use your new password.</p>
                    <?php
                    } else {
                    ?>
                        <p class="error"><?php echo @$response; ?></p>
                    <?php
                    }
                    ?>
        </form>
    </body>
</html>