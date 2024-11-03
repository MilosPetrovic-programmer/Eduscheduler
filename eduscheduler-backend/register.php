<?php
include "functions/init.php";
validate_registration();
?>
<div>
    <h1>Registration</h1>
    <form method="POST">
        <input type="text" name="name" placeholder="Name" required><br>
        <input type="text" name="surname" placeholder="Surname" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
        <input type="submit" name="register-submit" placeholder="Register Now"><br>
    </form>
</div>