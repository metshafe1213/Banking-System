<?php

$host = "localhost";
$user = "main";
$pwd = "1234";
$database = "users";

$conn = mysqli_connect($host, $user, $pwd, $database);

if(isset($_POST['signup_btn'])){

    $fullName = $_POST['fullName'];
    $uName = $_POST['userName'];
    $pWord = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if($pWord != $confirmPassword){
        header("Location: signup.html?error=nomatch");
        exit();
    }

    // check if username already taken
    $check = mysqli_query($conn, "SELECT * FROM login_info WHERE user_name='$uName'");
    if(mysqli_num_rows($check) > 0){
        header("Location: signup.html?error=taken");
        exit();
    }

    $hashedPassword = password_hash($pWord, PASSWORD_DEFAULT);
    $sql = "INSERT INTO login_info(name, user_name, password) VALUES('$fullName','$uName','$hashedPassword')";

    if(mysqli_query($conn, $sql)){
        // generate unique account number and create account
        $accountNumber = "ETB" . rand(1000000000, 9999999999);
        $accountSql = "INSERT INTO accounts(user_name, full_name, account_number, balance)
                       VALUES('$uName', '$fullName', '$accountNumber', 0.00)";
        mysqli_query($conn, $accountSql);

        header("Location: login.html?success=registered");
        exit();
    } else {
        header("Location: signup.html?error=failed");
        exit();
    }
}
?>
