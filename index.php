<?php
require_once "pdo.php";
session_start();
?>
<html>
<head>
<title> Welcome to Thrive
</title>
</head><body>
<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

if ( isset($_SESSION["success"]) ) {
    echo('<p style="color:green">'.$_SESSION["success"]."</p>\n");
    unset($_SESSION["success"]);
}
$myid = NULL;
if ( ! isset($_SESSION["name"]) ) { ?>
    <h1> Welcome to Thrive
    </h1>
   <p>Please <a href="login.php">Log In</a> </p>
    <p>Or <a href="register.php">Register here</a> </p>
<?php } else {
    $myid = $_SESSION['user_id'];
    ?>
    <p><a href="logout.php">Log Out</a> </p>
    <p><a href="add_course_student.php">Add Course | </a>
    <a href="add_event.php">Add Event <br> </a></p>
    <p><a href="add_bulletin.php">Bulletin Board | </a>
    <a href="add_discussion.php">Discussion Board</a> </p>
    <h1> Welcome to Your Thrive Profile
    </h1>
    <h3> My courses: </h3>
   
<?php }

//<p> <h3> My courses: </h3> </p>

echo('<table border="1">'."\n");
$stmt = $pdo->prepare(
    "SELECT *
    FROM takes
    WHERE user_id = :userid"
);
$stmt->execute(array(':userid' => $myid));

while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    echo "<tr><td>";
    echo(htmlentities($row['course_id']));
    echo("</td><td>");
    echo(htmlentities($row['name']));
    echo("</td><td>");
//index.php?id[]=123&version[]=3&id[]=234&version[]=4
    echo('<a href="delete_course_student.php?user_id='.$row['user_id'].'&course_id='.$row['course_id'].'  ">Delete</a>');
//    echo('<a href="index_chat.php?first_name='.$row['first_name'].'">Chat</a>');
    echo("</td></tr>\n");
}
?>

</table>


</body>
</html>
