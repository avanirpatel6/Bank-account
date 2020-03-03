<?php include_once("header.php");?>
<?php
//Display errrors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
echo "Executing: login.php <br>";
//Importing necessary scripts
include_once("database.php");
include_once("functions.php");
//Creating database object
$db = new DB();
//Parse AND trim the GET request
$username = trim($_GET["username"]);
$password = trim($_GET["password"]);
$service = $_GET["service"];
$account = $_GET["account"];
$amount = $_GET["amount"];
$newEmail = trim($_GET["email"]);
$newPass = trim($_GET["newPass"]);
$col = trim($_GET["col"]);
//Echoing necessary information
echo "The value of username is '$username'<br>The value of password is '$password'<br>The value of amount is '$amount'<br>";
//creating an array to store error log
$errorLog = array();
//check if amount is numeric or not
if($service == "transact"){
    if($amount == ""){
        array_push($errorLog, "Empty amount");
    }
    else if(!is_numeric($amount)){
        array_push($errorLog, "Non-numeric ammount");
    }
}   
//check if username is empty
if($username == ""){array_push($errorLog, "Empty username");}
//check if password is empty
if($password == ""){array_push($errorLog, "Empty password");}
//check if account is empty
if($account == ""){array_push($errorLog, "Empty account");}
//check if newEmail and new pass is empty
if($service == "alter"){
    if($newEmail == "" && $newPass == "" && $col == ""){
        array_push($errorLog, "NO alter data provided<br>");
    }
    else if($newEmail == "" && $col == "email"){
        array_push($errorLog, "Empty newEmail");
    } 
    else if($newPass == "" && $col == "password"){
        array_push($errorLog, "Empty newPass");
    }
}
//Display errror messages if any
if(count($errorLog) == 0){$dataOK = true;} else {$dataOK = false;}
if($dataOK){$state = -2;} else {$state = -1;}
//Check for auth
$auth = false;
//if any input is bad
if($state == -1){
    //echo log of errors
    echo "<b> Bad Input </b><br>";
    for($i = 0; $i < count($errorLog); $i++){
        $echoStr = $errorLog[$i]."<br>";
        echo $echoStr;
    }
    echo"<br>";
?>
<form action="./login.php" method = "GET">
            Username <input type = "text" id = "username" placeholder = "username" name ="username" required value = "<?php echo $username;?>">
            <br><br>
            Password <input type = "password" id = "password" placeholder = "password" name="password" required value = "<?php echo $password;?>">
            <br><br>
            Account <input type = "text" id = "account" placeholder = "account" name="account" value = "<?php echo $account;?>">
            <br><br>
            Service
            <select id = "service" name="service">
                <option value ="see" <?php if($service=="see"){echo "selected";}?>>See</option>
                <option value ="alter" <?php if($service=="alter"){echo "selected";}?>>alter</option>
                <option value ="transact" <?php if($service=="transact"){echo "selected";}?>>transact</option>
                <option value ="clear"<?php if($service=="clear"){echo "selected";}?>>clear</option>
            </select>
            <br><br>
            <!-- Field for transactions-->
           <div id = "transact" <?php echo ($service == "transact" ? "style = 'display:block;'" :"style='display:none;'");?> > Amount <input type = "text" name = "amount" placeholder = "amount" value ="<?php echo $amount;?>"> </div>
            <!--Fields for altering values-->
            <div id = "alter" <?php echo ($service == "alter" ? "style = 'display:block;'" :"style='display:none;'");?> >
            New Email <input type = "email" name = "email" class = "alter" placeholder = "email" value = "<?php echo $newEmail;?>"> <br><br>
            New Password <input type = "password" name = "newPass" class = "alter" placeholder = "password" value = "<?php echo $newPass;?>"> <br><br>
            Column <input type ="text" name="col" class = "alter" placeholder = "password or email" value = "<?php echo $col;?>"> <br><br>
            </div>
            <input type="submit" value="submit"> 
            
        </form>
<?php
} 
//data is good
if($state==-2){
    //Validate the username and pass
    $query = "SELECT * FROM users WHERE ucid = '$username' AND pass = '$password'"; 
    //echo the query for debugging
    echo "SQL credentials select statement is: $query <br>";
    $result = $db->query($query); 
    if(!$result){
        $state = 0;
        $dataOK = false;
        $errorLog = array("<b>Not Authenticated</b> <br><br>");
        echo $errorLog[0];
?>
<form action="./login.php" method = "GET">
            Username <input type = "text" id = "username" placeholder = "username" name ="username" required value = "<?php echo $username;?>">
            <br><br>
            Password <input type = "password" id = "password" placeholder = "password" name="password" required value = "<?php echo $password;?>">
            <br><br>
            Account <input type = "text" id = "account" placeholder = "account" name="account" value = "<?php echo $account;?>">
            <br><br>
            Service
            <select id = "service" name="service">
                <option value ="see" <?php if($service=="see"){echo "selected";}?>>See</option>
                <option value ="alter" <?php if($service=="alter"){echo "selected";}?>>alter</option>
                <option value ="transact" <?php if($service=="transact"){echo "selected";}?>>transact</option>
                <option value ="clear"<?php if($service=="clear"){echo "selected";}?>>clear</option>
            </select>
            <br><br>
            <!-- Field for transactions-->
           <div id = "transact" <?php echo ($service == "transact" ? "style = 'display:block;'" :"style='display:none;'");?> > Amount <input type = "text" name = "amount" placeholder = "amount" value ="<?php echo $amount;?>"> </div>
            <!--Fields for altering values-->
            <div id = "alter" <?php echo ($service == "alter" ? "style = 'display:block;'" :"style='display:none;'");?> >
            New Email <input type = "email" name = "email" class = "alter" placeholder = "email" value = "<?php echo $newEmail;?>"> <br><br>
            New Password <input type = "password" name = "newPass" class = "alter" placeholder = "password" value = "<?php echo $newPass;?>"> <br><br>
            Column <input type ="text" name="col" class = "alter" placeholder = "password or email" value = "<?php echo $col;?>"> <br><br>
            </div>
            <input type="submit" value="submit"> 
            
        </form>
<?php
    }
    else{
        //Update the state and dataOK variable
        $dataOK = true;
        $state = 4; 
    }
}
if($state==4 && $_GET["pin"] ==""){
    //Generate random pin and store it in database hacky way of doing pin
    $pin = pinGenerator($db, $username);
    //Echoing pin to the form
    echo "<b>Copy pin like from text message $pin</b><br><br>";
    //Email the pin to the user's email
    $email = $result[0]["email"];
    sendEmail($email, $pin);
?>
<form action="./login.php" method = "GET">
            Username <input type = "text" id = "username" placeholder = "username" name ="username" required value = "<?php echo $username;?>">
            <br><br>
            Password <input type = "password" id = "password" placeholder = "password" name="password" required value = "<?php echo $password;?>">
            <br><br>
            Pin <input type = "text" id = "pin" placeholder = "pin" name = "pin">
            <br><br>
            Account <input type = "text" id = "account" placeholder = "account" name="account" value = "<?php echo $account;?>">
            <br><br>
            Service
            <select id = "service" name="service">
                <option value ="see" <?php if($service=="see"){echo "selected";}?>>See</option>
                <option value ="alter" <?php if($service=="alter"){echo "selected";}?>>alter</option>
                <option value ="transact" <?php if($service=="transact"){echo "selected";}?>>transact</option>
                <option value ="clear"<?php if($service=="clear"){echo "selected";}?>>clear</option>
            </select>
            <br><br>
            <!-- Field for transactions-->
           <div id = "transact" <?php echo ($service == "transact" ? "style = 'display:block;'" :"style='display:none;'");?> > Amount <input type = "text" name = "amount" placeholder = "amount" value ="<?php echo $amount;?>"> </div>
            <!--Fields for altering values-->
            <div id = "alter" <?php echo ($service == "alter" ? "style = 'display:block;'" :"style='display:none;'");?> >
            New Email <input type = "email" name = "email" class = "alter" placeholder = "email" value = "<?php echo $newEmail;?>"> <br><br>
            New Password <input type = "password" name = "newPass" class = "alter" placeholder = "password" value = "<?php echo $newPass;?>"> <br><br>
            Column <input type ="text" name="col" class = "alter" placeholder = "password or email" value = "<?php echo $col;?>"> <br><br>
            </div>
            <input type="submit" value="submit"> 
            
        </form>
<?php
}
else{
    //Retrieve pin from db
    $DBPin = retrievePin($db, $username);
    //Retrieve pin from form
    $newPin = $_GET["pin"]; //why? no  $pin is the generated pin   it is the conditionals that we are
    //echo "Generated Pin: $DBPin <br>";
    //echo "User entered Pin: $newPin<br>";
    if($newPin == $DBPin){
        //echo "correct pin entered";
        $state = -2;
        $dataOK = true;
        $auth = true;
    }
    else{
        $state = 1;
        $dataOK = false;
    }
}
if($state==1){
    $pin = pinGenerator($db, $username);
    //Echoing pin to the form
    echo "<b>Your new pin is: $pin</b><br><br>";
    //Email the pin to the user's email
    $email = $result[0]["email"];
    sendEmail($email, $pin);
    ?>
    <form action="./login.php" method = "GET">
            Username <input type = "text" id = "username" placeholder = "username" name ="username" required value = "<?php echo $username;?>">
            <br><br>
            Password <input type = "password" id = "password" placeholder = "password" name="password" required value = "<?php echo $password;?>">
            <br><br>
            Pin <input type = "text" id = "pin" placeholder = "pin" name = "pin">
            <br><br>
            Account <input type = "text" id = "account" placeholder = "account" name="account" value = "<?php echo $account;?>">
            <br><br>
            Service
            <select id = "service" name="service">
                <option value ="see" <?php if($service=="see"){echo "selected";}?>>See</option>
                <option value ="alter" <?php if($service=="alter"){echo "selected";}?>>alter</option>
                <option value ="transact" <?php if($service=="transact"){echo "selected";}?>>transact</option>
                <option value ="clear"<?php if($service=="clear"){echo "selected";}?>>clear</option>
            </select>
            <br><br>
            <!-- Field for transactions-->
           <div id = "transact" <?php echo ($service == "transact" ? "style = 'display:block;'" :"style='display:none;'");?> > Amount <input type = "text" name = "amount" placeholder = "amount" value ="<?php echo $amount;?>"> </div>
            <!--Fields for altering values-->
            <div id = "alter" <?php echo ($service == "alter" ? "style = 'display:block;'" :"style='display:none;'");?> >
            New Email <input type = "email" name = "email" class = "alter" placeholder = "email" value = "<?php echo $newEmail;?>"> <br><br>
            New Password <input type = "password" name = "newPass" class = "alter" placeholder = "password" value = "<?php echo $newPass;?>"> <br><br>
            Column <input type ="text" name="col" class = "alter" placeholder = "password or email" value = "<?php echo $col;?>"> <br><br>
            </div>
            <input type="submit" value="submit"> 
            
        </form>
<?php
}
if($auth){
     //reset the pin to 0
     $resetPin = "UPDATE users SET pin = '0' WHERE ucid = '$username' AND pass = '$password'";
     $result = $db->query($resetPin);
     //echoing debugging messages
     echo "SQL pin reset to 0 statement is: $resetPin <br><br>";
     echo "\$state is -2 <br><br>"; //state is gonna be -2
    //carry out the requested service
    switch($service){
        case "see": see($db,$username, $account); //i can hear you
        case "transact": transact($db,$username, $account, doubleval($amount));
        //case "alter": alter($username, $alterData, $col); //But how do we know if $alterData is email or password in the function but what if col is null
        case "clear": clear($db,$username, $account);
    }
    echo "SQL pin reset to 0 statement is: $resetPin<br><br>";
}
?>


<?php include_once("footer.php");?> 