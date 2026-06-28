<?php

session_start();

$host = "localhost";
$user = "main";
$pwd = "1234";
$database = "users";

$conn = mysqli_connect($host, $user, $pwd, $database);

if(isset($_POST['login_btn'])){

    $uName = $_POST['userName'];
    $pWord = $_POST['password'];

    $sql = "SELECT * FROM login_info WHERE user_name='$uName'";
    $results = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($results);

    if(mysqli_num_rows($results) > 0){
        $userPassword = $row['password'];
        if(password_verify($pWord, $userPassword)){
            $_SESSION['userName'] = $uName;
            header("Location: dashboard.html");
            exit();
        } else {
            header("Location: login.html?error=wrongpassword");
            exit();
        }
    } else {
        header("Location: login.html?error=notfound");
        exit();
    }
}
?>
