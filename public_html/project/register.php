<?php
    require_once(__DIR__ . "/../../partials/nav.php");
?>

<form onsubmit="return validate(this)" method="POST">
    <div>
        <label for="email">Email</label>
        <input id="email" type="email" name="email" required />
    </div>
    <div>
        <label for="pw">Password</label>
        <input type="password" id="pw" name="password" required minlength="8" />
    </div>
    <div>
        <label for="username">Username</label>
        <input type="text" name="username" required maxlength="30" />
    </div>
    <div>
        <label for="confirm">Confirm</label>
        <input type="password" name="confirm" required minlength="8" />
    </div>
    <input type="submit" value="Register" />
</form>
<script>
    function validate(form) {
        //TODO 1: implement JavaScript validation
        //ensure it returns false for an error and true for success

        return true;
    }
</script>
<?php
 //TODO 2: add PHP Code
 if(isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["confirm"])) {
    $email = se($_POST,"email","",false);//$_POST["email"];
    $password = se($_POST,"password","",false); //$_POST["password"];
    $confirm = se($_POST,"confirm","",false);//$_POST["confirm"];
    $username = se($_POST, "username", "", false);
    //TODO 3
    $hasError = false;
    if (empty($email)) {
        echo "Email must be provided <br>";
        $hasError = true;
    }
    //sanitize
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    //validate
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo "Please enter a valid email <br>";
        $hasError = true;
    }

    if (empty($password)) {
        echo "Password must be provided <br>";
        $hasError = true;
    }

    if (empty($confirm)) {
        echo "Confirm must be provided <br>";
        $hasError = true;
    }

    if(strlen($password) < 8) {
        echo "Password must be at least 8 characters long <br>";
        $hasError = true;
    }

    if(strlen($password) > 8 && $password!=$confirm) {
        echo "Passwords must match <br>";
        $hasError = true;
    }

    if (!preg_match('/^[a-z0-9_-]{3,30}$/', $username)) {
        flash(
            "Username must be lowercase, alphanumerical, and can only contain _ or -",
            "warning"
        );
        $hasError =true;
        }
    

    if(!$hasError) {
       // echo "Welcome, $email";
       //TODO 4
       $hash = password_hash($password, PASSWORD_BCRYPT);
       $db = getDB();
       $stmt = $db->prepare("INSERT INTO Users(email, password, username) VALUES (:email, :password, :username)");
       try{
        $r = $stmt->execute([":email"=>$email, ":password"=>$hash, "username" => $username]);
        echo "Successfully register!";
       }
       catch(Exception $e){
            echo "There was an error registering<br>";
            echo "<pre>" . var_export($e, true) . "</pre>";
       }
    }
 
 }
?>