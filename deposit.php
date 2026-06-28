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

// get the amount from the AJAX request
$input = json_decode(file_get_contents("php://input"), true);
$amount = $input['amount'];

// validate amount
if($amount <= 0){
    echo json_encode(['error' => 'Invalid amount']);
    exit();
}

// get user account
$accountResult = mysqli_query($conn, "SELECT * FROM accounts WHERE user_name='$uName'");
$account = mysqli_fetch_assoc($accountResult);
$accountNumber = $account['account_number'];

// update balance
$newBalance = $account['balance'] + $amount;
mysqli_query($conn, "UPDATE accounts SET balance='$newBalance' WHERE account_number='$accountNumber'");

// record transaction
mysqli_query($conn, "INSERT INTO transactions(account_number, type, amount, description)
    VALUES('$accountNumber', 'Deposit', '$amount', 'Deposit of $amount ETB')");

echo json_encode([
    'success' => true,
    'newBalance' => $newBalance
]);
?>
