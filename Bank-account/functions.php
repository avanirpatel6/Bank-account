<?php

function pinGenerator($database, $username){
    //connect to database
    $db = $database;
    //generate the pin and convert it to string
    $pin = strval(mt_rand(1001, 9999));
    //store the pin in database
    $insertPin = "UPDATE users SET pin = '$pin' WHERE ucid = '$username'";
    $result = $db->query($insertPin);
    //return pin
    return $pin;
} 
function retrievePin($database, $username){
    //connect to database
    $db = $database;
    //retrieve pin from database
    $getPin = "SELECT pin FROM users WHERE ucid = '$username'";
    $result = $db->query($getPin);
    //return pin
    return $result[0]["pin"];
}
function sendEmail($email, $pin){
    //set the reciever's email
    $to = $email;
    //set the senders's email
    $from = "account-noreply@njitbank.com";
    //message that includes the pin
    $message="Your verification code is:\n\n".$pin."\n\nThanks,\nNJIT Student Bank";
    //necessary headers
    $headers="From: $from\n";
    //necessary subject
    $subject="Verification code";
    //send the email
    mail($to, $headers, $message, $headers);
}
function see($database,$username, $account){
    //connect to database
    $db = $database;
    //retrieving info from accounts table
    $accountQuery = "SELECT * FROM accounts WHERE ucid = '$username' AND account = '$account' ORDER BY recent DESC";
    $getAccount = $db->query($accountQuery);
    //number of rows retrieved from accounts table
    $numRowsA = sizeof($getAccount);
    //retrieving info from transactiosn table
    $transactionsQuery = "SELECT * FROM transactions WHERE ucid = '$username' AND account = '$account' ORDER BY timestamp DESC LIMIT 3";
    $getTransactions = $db->query($transactionsQuery);
    //check if there are any transactions 
    
    //number of rows retireved from transactions table
    $numRowsT = sizeof($getTransactions); 
    //echoing information from accounts table
    echo "$numRowsA rows retrieved from accounts<br>$numRowsT rows retrieved from transactions<br><br><hr><br>";
    echo "Information on account follows<br><br>account: $account || balance:".$getAccount[0]['balance']." || recent: ".$getAccount[0]['recent']."<br><br><hr><br>";
    //echoing information from transactions table
    echo "Information on account transactions follows<br><br>";
    if($numRowsT==0){
        echo "No transactions<br>";
        return;
    }
    foreach($getTransactions as $row){
        echo "amount: ". $row["amount"]." || timestamp: ".$row["timestamp"]."<br>";
    }
    echo "<br><hr><br>";
}
/*
function alter($username){
    //alter the info for the user
}*/
function transact($database,$username, $account, $amount){
    //connect to the database
    $db = $database;
    //check whether the account exists or not
    $updateAcctQuery = "UPDATE accounts SET recent = NOW(), balance = balance + $amount WHERE ucid = '$username' AND account = '$account' AND balance + $amount >= 0";
    //echo the query
    echo "$updateAcctQuery<br><br>";
    //execute the update query
    $updateAccount = $db->execute($updateAcctQuery);
    //check if overdraft or not
    if($updateAccount == 0){
        echo "<b>Overdraft attempt rejected</b><br>";
        return;
    }
    //Inserting the transaction in the database
    $transaction = "INSERT INTO transactions(ucid, account, amount, mail) VALUES ('$username', '$account', $amount, 'N')";
    //echo transaction
    echo "$transaction<br><br>";
    $insertTransaction = $db->query($transaction);
    //display account information after transaction
    see($database, $username,$account);

}

function clear($database,$username,$account){
    //connect to database
    $db = $database;
    //Deleting the relevant transactions from database
    $deleteTransQuery = "DELETE FROM transactions WHERE ucid = '$username' AND account = '$account'";
    $deleteTransactions = $db->execute($deleteTransQuery);
    //Check if the transactions were deleted or not
    if($deleteTransactions == 0){
        echo "Could not delete transactions";
        return;
    }
    //Update the account balance in te database
    $updateAcctQuery = "UPDATE accounts SET recent = NOW(), balance = 0.00 WHERE ucid = '$username' AND account = '$account'";
    $updateAccount = $db -> execute($updateAcctQuery);
    //check if the account balance is updated
    if($updateAccount == 0){
        echo "Could not update the account";
        return;
    }
    //echo relevant information
    echo "Clear $username's account  & delete its transactions,<br> SQL update account: $updateAcctQuery.<br>SQL deletes transaction: $deleteTransQuery.<br><br>Accounts & Transactions summary after Clear.<br>";
    //display account information after clear
    see($database, $username, $account);    
}

?>