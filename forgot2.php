<?php
$con = mysqli_connect("localhost", "root", "", "organic_shop_db") or die("Couldn't connect");

if (isset($_POST['forgot'])) {
    $email = mysqli_real_escape_string($con, $_POST['Email']);

    $stmt = $con->prepare("SELECT * FROM tbl_login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $row = $res->fetch_assoc();
        $new_password = rand(100000, 999999); // Random 6-digit number
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password
        $stmt_update = $con->prepare("UPDATE tbl_login SET password = ? WHERE email = ?");
        $stmt_update->bind_param("ss", $hashed_password, $email);
        $stmt_update->execute();

        // Send email
        $to = $email;
        $subject = "Your Recovered Password";
        $message = "Please use this password to login: " . $new_password;
        $headers = "From: admin@yourdomain.com";

        if (mail($to, $subject, $message, $headers)) {
            echo "<script>alert('Your new password has been sent to your email.');</script>";
        } else {
            echo "<script>alert('Failed to send email.');</script>";
        }
    } else {
        echo "<script>alert('Email not found in database.');</script>";
    }
}
?>

<html>
<head>
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" >

<link rel="stylesheet" href="styles.css" >

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style>
	body {
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #eee;
}

.form-signin {
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
.form-signin .form-signin-heading,
.form-signin .checkbox {
  margin-bottom: 10px;
}
.form-signin .checkbox {
  font-weight: normal;
}
.form-signin .form-control {
  position: relative;
  height: auto;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}
.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
	</style>
    <title>forgot</title>
	</head>
    <form class="form-signin" method="POST">
    <br>
    <br>
    <br>
    <center><h2 class="form-signin-heading">Forgot Password</h2></center>
    <br>
    <br>
    <br>
    <br>
    <div class="input-group">
    <span class="input-group-addon" id="basic-addon1">@</span>
    <input type="text" name="Email" id="Email" class="form-control" placeholder="Email" required>
    </div>
        <br>
    <button class="btn btn-lg btn-primary btn-block" id="forgot" name="forgot"type="submit">Forgot Password</button>
    <BR>
    <a class="btn btn-lg btn-primary btn-block" href="login.php">Login</a>
    </form>
</html>