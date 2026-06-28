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

// get data from AJAX request
$input = json_decode(file_get_contents("php://input"), true);
$amount = $input['amount'];
$recipientAccount = $input['recipientAccount'];

// validate amount
if($amount <= 0){
    echo json_encode(['error' => 'Invalid amount']);
    exit();
}

// get sender account
$senderResult = mysqli_query($conn, "SELECT * FROM accounts WHERE user_name='$uName'");
$sender = mysqli_fetch_assoc($senderResult);
$senderAccount = $sender['account_number'];

// check if sending to own account
if($recipientAccount == $senderAccount){
    echo json_encode(['error' => 'Cannot transfer to your own account']);
    exit();
}

// check sufficient funds
if($amount > $sender['balance']){
    echo json_encode(['error' => 'Insufficient funds']);
    exit();
}

// check if recipient exists
$recipientResult = mysqli_query($conn, "SELECT * FROM accounts WHERE account_number='$recipientAccount'");
if(mysqli_num_rows($recipientResult) == 0){
    echo json_encode(['error' => 'Recipient account not found']);
    exit();
}
$recipient = mysqli_fetch_assoc($recipientResult);

// update sender balance
$newSenderBalance = $sender['balance'] - $amount;
mysqli_query($conn, "UPDATE accounts SET balance='$newSenderBalance' WHERE account_number='$senderAccount'");

// update recipient balance
$newRecipientBalance = $recipient['balance'] + $amount;
mysqli_query($conn, "UPDATE accounts SET balance='$newRecipientBalance' WHERE account_number='$recipientAccount'");

// record transaction for sender
mysqli_query($conn, "INSERT INTO transactions(account_number, type, amount, description)
    VALUES('$senderAccount', 'Transfer Out', '$amount', 'Transfer to $recipientAccount')");

// record transaction for recipient
mysqli_query($conn, "INSERT INTO transactions(account_number, type, amount, description)
    VALUES('$recipientAccount', 'Transfer In', '$amount', 'Transfer from $senderAccount')");

echo json_encode([
    'success' => true,
    'newBalance' => $newSenderBalance
]);
?>
