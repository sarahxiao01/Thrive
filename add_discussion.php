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


$stmt = $pdo->prepare("SELECT * FROM users where user_id = :xyz");
$stmt->execute(array(":xyz" => $_SESSION['user_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Not logged in';
    header( 'Location: index.php' ) ;
    return;
    }
//$currtime = NULL;

if ( isset($_POST['mypost']) ) {

    // Data validation
    if ( strlen($_POST['mypost']) < 1 ) {
        header("Location: index.php");
        return;
    }

    $stmt = $pdo->prepare('INSERT INTO discussion
    (user_id, name, content) VALUES ( :uid, :nam, :content)');
    $stmt->execute(array(
    ':uid' => $_SESSION['user_id'],
    ':nam' => $_SESSION['name'],
    ':content' => $_POST['mypost'])
    );
    $_SESSION['success'] = 'Discussion Posted';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern
                          


?>
<p>Add A New Post</p>
<form method="post">
<p>Say something:
<input type="text" name="mypost"></p>


<p><input type="submit" value="Post"/>
<a href="index.php">Cancel</a></p>
</form>
