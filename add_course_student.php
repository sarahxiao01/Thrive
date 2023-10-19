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
$myid =  $_SESSION['user_id'];


if ( isset($_POST['course']) && isset($_POST['dept'])){
    if ( strlen($_POST['course'] ) <1 || strlen($_POST['dept'] ) <1){
        header( 'Location: index.php' ) ;
        return;
}

    $mycourse = NULL;
    $hercourse = NULL;
    $number = "CS-UY" . " " . $_POST['course'];

    
    
    $stmt = $pdo->prepare(
        "SELECT *
        FROM Courses
        WHERE course_no = :course"
    );
    $stmt->execute(array(':course' => $number));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row !== false) {
        $mycourse = $row['course_id'];
        $hercourse = $row['name'];
        $stmt = $pdo->prepare(
            "SELECT *
            FROM takes
            WHERE user_id = :userid AND course_id = :course"
        );
        $stmt->execute(array(':userid' => $myid, ':course' => $mycourse ));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row !== false) {
            $_SESSION['error'] = 'Course already exists';
            header( 'Location: index.php' );
            return;
        }
        
        $stmt = $pdo->prepare(
            'INSERT INTO takes (user_id, course_id, name) VALUES (:userid, :courseid, :course_name)'
        );
        $stmt->execute(
            array(
            ':userid' => $_SESSION['user_id'],
            ':courseid' => $mycourse,
            ':course_name' => $hercourse)
                       
        );

        $_SESSION['success'] = 'Course Added';
        header( 'Location: index.php' );
        return;
    }
    $_SESSION['error'] = 'Course not found';
    header( 'Location: index.php' );
    return;
}

    

?>
<html>
<head>
</head>
<body>
<h1>Add A New Course</h1>
<form method="post">


<!-- <p>Course Dept:
<input type="text" name="dept" value = "CS-UY"></p> -->

<p><label for="inp">Course Dept:
    <select name="dept" id="inp">
    <option value="0" selected > CS-UY </option>
    </select>
   </p>

<p>Course number:
<input type="text" name="course"></p>
<p>Course not found? Request a course:</p>
<p> Course name:
<input type="text" name="request_name"></p>
<p> Course number:
<input type="text" name="request_no"></p>

<p><input type="submit" value="Add My Course"/>
<a href="index.php">Cancel</a></p>
</form>
</body>
</html>
