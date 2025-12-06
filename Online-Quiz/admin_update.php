<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

session_start();
include_once 'database.php';

// $email = $_SESSION['email'];

// /* --- Only admin can do these --- */
// if (!isset($_SESSION['key']) || $_SESSION['key']!='suryapinky') {
//     header("location:dashboard.php?q=0");
//     exit();
// }

/* -------- add quiz -------- */
if (isset($_GET['q']) && $_GET['q'] == 'addquiz') {
    $name  = ucwords(strtolower($_POST['name']));
    $total = $_POST['total'];
    $sahi  = $_POST['right'];
    $wrong = $_POST['wrong'];
    $id    = uniqid();

    mysqli_query($con,
        "INSERT INTO quiz (eid, title, sahi, wrong, total, date) 
         VALUES ('$id','$name','$sahi','$wrong','$total',NOW())"
    ) or die("Error in query: " . mysqli_error($con));

    header("location:dashboard.php?q=4&msg=success&step=2&eid=$id&n=$total");
    exit();
}

/* -------- add questions -------- */
if (isset($_GET['q']) && $_GET['q'] == 'addqns') {
    $n   = $_GET['n'];
    $eid = $_GET['eid'];
    $ch  = $_GET['ch'];

    for ($i=1; $i<=$n; $i++) {
        $qid = uniqid();
        $qns = $_POST['qns'.$i];

        mysqli_query($con,"INSERT INTO questions VALUES ('$eid','$qid','$qns','$ch','$i')");

        // Generate option IDs
        $oaid = uniqid(); $obid = uniqid(); $ocid = uniqid(); $odid = uniqid();

        // Read options from form (use q1a, q1b, q1c, q1d style names)
        // Read options from form (use 11, 12, 13, 14)
$a = $_POST[$i.'1'];
$b = $_POST[$i.'2'];
$c = $_POST[$i.'3'];
$d = $_POST[$i.'4'];

if (empty($a) || empty($b) || empty($c) || empty($d)) {
    die("Error: Missing options for question $i");
}


        // Insert options into DB
        mysqli_query($con,"INSERT INTO options (qid, `option`, optionid) VALUES ('$qid','$a','$oaid')");
        mysqli_query($con,"INSERT INTO options (qid, `option`, optionid) VALUES ('$qid','$b','$obid')");
        mysqli_query($con,"INSERT INTO options (qid, `option`, optionid) VALUES ('$qid','$c','$ocid')");
        mysqli_query($con,"INSERT INTO options (qid, `option`, optionid) VALUES ('$qid','$d','$odid')");

        // Correct answer
        $e = $_POST['ans'.$i];
        switch($e){
            case 'a': $ansid=$oaid; break;
            case 'b': $ansid=$obid; break;
            case 'c': $ansid=$ocid; break;
            case 'd': $ansid=$odid; break;
            default: $ansid=$oaid;
        }
        mysqli_query($con,"INSERT INTO answer VALUES ('$qid','$ansid')");
    }

    header("location:dashboard.php?q=0");
    exit();
}

/* -------- delete user -------- */
if (isset($_GET['q']) && $_GET['q'] == 'deleteuser') {
    $demail = $_GET['demail'];

    // Delete user's rank entry
    mysqli_query($con, "DELETE FROM rank WHERE email='$demail'") 
        or die("Error deleting rank: " . mysqli_error($con));

    // Delete user's history (optional but keeps DB clean)
    mysqli_query($con, "DELETE FROM history WHERE email='$demail'") 
        or die("Error deleting history: " . mysqli_error($con));

    // Finally delete user
    mysqli_query($con, "DELETE FROM user WHERE email='$demail'") 
        or die("Error deleting user: " . mysqli_error($con));

    header("location:dashboard.php?q=1&msg=deleted");
    exit();
}



// ---------------- Delete Quiz ----------------
if (isset($_GET['q']) && $_GET['q'] == 'deletequiz') {
    $eid = $_GET['eid'];

    // Step 1: Delete from history first
    mysqli_query($con, "DELETE FROM history WHERE eid='$eid'");

    //  Step 2: Delete related answers and options
    $result = mysqli_query($con, "SELECT qid FROM questions WHERE eid='$eid'");
    while ($row = mysqli_fetch_assoc($result)) {
        $qid = $row['qid'];
        mysqli_query($con, "DELETE FROM options WHERE qid='$qid'");
        mysqli_query($con, "DELETE FROM answer WHERE qid='$qid'");
    }

    //  Step 3: Delete questions
    mysqli_query($con, "DELETE FROM questions WHERE eid='$eid'");

    //  Step 4: Finally, delete the quiz
    mysqli_query($con, "DELETE FROM quiz WHERE eid='$eid'");

    //  Redirect with success message
    header("location:dashboard.php?q=5&msg=quiz_deleted");
    exit();
}


// Change Admin Password
if (@$_GET['q'] == 'changepass') {
    $email = $_SESSION['email'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch admin current password from DB
    $res = mysqli_query($con, "SELECT password FROM admin WHERE email='$email'") or die('Error');
    $row = mysqli_fetch_assoc($res);

    if ($row && $row['password'] === $current_password) {
    if ($new_password === $confirm_password) {
        mysqli_query($con, "UPDATE admin SET password='$new_password' WHERE email='$email'") or die('Error');
        header("location:dashboard.php?q=6&msg=pass_success");
    } else {
        header("location:dashboard.php?q=6&msg=pass_mismatch");
    }
} else {
    header("location:dashboard.php?q=6&msg=pass_wrong");
}

    exit();
}
?>