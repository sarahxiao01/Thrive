<?php
require_once "pdo.php";
session_start();


if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

if ( isset($_SESSION["success"]) ) {
    echo('<p style="color:green">'.$_SESSION["success"]."</p>\n");
    unset($_SESSION["success"]);
}

if ( isset($_POST["name"]) && isset($_POST['email']) && isset($_POST["password"]) ) {
    if ( strlen($_POST['name']) < 1 || strlen($_POST['password']) < 1 || strlen($_POST['email']) < 1 ) {
        $_SESSION['error'] = 'All fields are required';
        header("Location: register.php");
        return;
    }

    $stmt = $pdo->prepare("SELECT * FROM users where name = :xyz");
    $stmt->execute(array(":xyz" => $_POST['name']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ( $row !== false ) {
        $_SESSION['error'] = 'Username already in use';
        header( 'Location: register.php' ) ;
        return;
        }
    if ( strpos($_POST['email'],'@') === false ) {
        $_SESSION['error'] = 'Invalid email address';
        header("Location: register.php");
        return;
    }
    $stmt = $pdo->prepare("SELECT * FROM users where email = :xyz");
    $stmt->execute(array(":xyz" => $_POST['email']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ( $row !== false ) {
        $_SESSION['error'] = 'Email already in use';
        header( 'Location: register.php' ) ;
        return;
        }

    $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES ( :nam, :em, :pas)');
    $stmt->execute(array(
    ':nam' => $_POST['name'], ':em' => $_POST['email'], ':pas' => $_POST['password'])
    );
    $_SESSION['success'] = 'User Added';
    header( 'Location: index.php' ) ;
    return;
    
    
}


//
//if ( $row === false ) {
//    $_SESSION['error'] = 'Not logged in';
//    header( 'Location: index.php' ) ;
//    return;
//    }


// Flash pattern
                          


?>
<h1> Register a New User </h1>
<form method="post">
<p>Username:
<input type="text" name="name"></p>
<p>Email:
<input type="text" name="email"></p>
<p>Password:
<input type="text" name="password"></p>

<p><input type="submit" value="Register"/>
<a href="index.php">Cancel</a></p>
</form>
