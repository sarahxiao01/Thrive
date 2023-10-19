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

if ( ! isset($_GET['course_id']) ) {
  $_SESSION['error'] = "Missing Course ID";
  header('Location: index.php');
  return;
}

if ( ! isset($_GET['user_id']) ) {
  $_SESSION['error'] = "Missing User ID";
  header('Location: index.php');
  return;
}



$stmt = $pdo->prepare("SELECT * FROM takes where user_id = :yxz AND course_id = :xyz");
$stmt->execute(array(":yxz" => $_GET['user_id'], ":xyz" => $_GET['course_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Course does not exist';
    header( 'Location: index.php' ) ;
    return;
}

$course_name = $row['name'] ;

if ( $_GET['user_id'] != $_SESSION['user_id'] ) {
  $_SESSION['error'] = "User is not registered in this course";
  header('Location: index.php');
  return;
}


//$stmt = $pdo->prepare("SELECT * FROM users, Profile where profile_id = :xyz AND users.user_id = Profile.user_id");
//$stmt->execute(array(":xyz" => $_GET['profile_id']));
//$row = $stmt->fetch(PDO::FETCH_ASSOC);
//if ( $row['user_id'] != $_SESSION['user_id'] ) {
//    $_SESSION['error'] = 'Entry does not belong to user';
//    header( 'Location: index.php' ) ;
//    return;
//}

if ( isset($_POST['delete']) && isset($_POST['course_id']) ) {
    $sql = "DELETE FROM takes WHERE user_id = :tip AND course_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(":tip" => $_POST['user_id'], ":zip" => $_POST['course_id']));
    $_SESSION['success'] = 'Course deleted from my course list';
    header( 'Location: index.php' ) ;
    return;
}


?>
<p>Confirm: Deleting <?= htmlentities($course_name) ?></p>

<form method="post">
<input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
<input type="hidden" name="course_id" value="<?= $row['course_id'] ?>">
<input type="submit" value="Delete" name="delete">
<a href="index.php">Cancel</a>
</form>
