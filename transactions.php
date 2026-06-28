<?php

session_start();
if(!isset($_SESSION['userName'])){
    echo json_encode(['error' => 'not logged in']);
    exit();
}

$host = "localhost";
$user = "main";
$pwd = "1234";
$database = "users";
$conn = mysqli_connect($host, $user, $pwd, $database);

$uName = $_SESSION['userName'];

// get user account
$accountResult = mysqli_query($conn, "SELECT * FROM accounts WHERE user_name='$uName'");
$account = mysqli_fetch_assoc($accountResult);
$accountNumber = $account['account_number'];

// get all transactions
$transResult = mysqli_query($conn, "SELECT * FROM transactions WHERE account_number='$accountNumber' ORDER BY date DESC");

$transactions = [];
while($trans = mysqli_fetch_assoc($transResult)){
    $transactions[] = $trans;
}

echo json_encode([
    'transactions' => $transactions
]);
?>
