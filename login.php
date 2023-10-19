<?php
    require_once "pdo.php";
    session_start();
    $salt = "XyZzy12*_";
    if ( isset($_POST["name"]) && isset($_POST["password"]) ) {
        if ( strlen($_POST['name']) < 1 || strlen($_POST['password']) < 1) {
            $_SESSION['error'] = 'Username and password cannot be empty';
            header("Location: login.php");
            return;
        }

//        $check = hash('md5', $salt.$_POST['password']);
        $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE name = :name AND password = :password');
        $stmt->execute(array( ':name' => $_POST['name'], ':password' => $_POST['password']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ( $row !== false ) {
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['success'] = 'Login Success';
            header("Location: index.php");
            return;
            }
        else {
            $_SESSION["error"] = "Incorrect username or password.";
            header( 'Location: login.php' ) ;
            return;
        }
    }
?>
<html>
<head>
</head>
<body style="font-family: sans-serif;">
<h1>Please Log In</h1>
<?php
    if ( isset($_SESSION["error"]) ) {
        echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
        unset($_SESSION["error"]);
    }
    if ( isset($_SESSION["success"]) ) {
        echo('<p style="color:green">'.$_SESSION["success"]."</p>\n");
        unset($_SESSION["success"]);
}
    
?>
<form method="post">
<p>Username: <input type="text" name="name" ></p>
<p>Password: <input type="password" name="password"></p>
<p><input type="submit" value="Log In">
<a href="index.php">Cancel</a></p>
</form>
</body>
