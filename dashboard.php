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

// fetch account info
$accountResult = mysqli_query($conn, "SELECT * FROM accounts WHERE user_name='$uName'");
$account = mysqli_fetch_assoc($accountResult);

// fetch last 4 transactions
$transResult = mysqli_query($conn, "SELECT * FROM transactions WHERE account_number='{$account['account_number']}' ORDER BY date DESC LIMIT 4");

$transactions = [];
while($trans = mysqli_fetch_assoc($transResult)){
    $transactions[] = $trans;
}

echo json_encode([
    'userName' => $uName,
    'fullName' => $account['full_name'],
    'accountNumber' => $account['account_number'],
    'balance' => $account['balance'],
    'transactions' => $transactions
]);
?>
