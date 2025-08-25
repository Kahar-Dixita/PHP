<?php
include_once 'database.php';
session_start();
$email = $_SESSION['email'];

/* -------- take quiz answer -------- */
if (@$_GET['q'] == 'quiz' && @$_GET['step'] == 2) {
    $eid   = $_GET['eid'];
    $sn    = $_GET['n'];     // current question number
    $total = $_GET['t'];     // total questions
    $qid   = $_GET['qid'];
    $ans   = $_POST['ans'];

    // correct answer id
    $q = mysqli_query($con, "SELECT ansid FROM answer WHERE qid='$qid'");
    $row = mysqli_fetch_array($q);
    $ansid = $row['ansid'];

    // marks per correct answer for this quiz
    $sahiMarks = mysqli_fetch_array(mysqli_query($con, "SELECT sahi FROM quiz WHERE eid='$eid'"))['sahi'];

    // first question -> initialize history
    if ($sn == 1) {
        mysqli_query($con, "INSERT INTO history (email, eid, score, level, sahi, wrong, date)
                            VALUES ('$email', '$eid', 0, 0, 0, 0, NOW())");
    }

    // get current history row
    $history = mysqli_fetch_array(mysqli_query($con, "SELECT score, sahi, wrong FROM history WHERE eid='$eid' AND email='$email'"));

    $score = $history['score'];
    $right = $history['sahi'];
    $wrong = $history['wrong'];

    // check answer
    if ($ans == $ansid) {
        $score += $sahiMarks;
        $right += 1;
    } else {
        $wrong += 1;
    }

    // update history
    mysqli_query($con, "UPDATE history SET score=$score, sahi=$right, wrong=$wrong, level=$sn, date=NOW()
                        WHERE email='$email' AND eid='$eid'");

    // Save answer in session
    $_SESSION['answers'][$eid][$sn] = $ans;

    // Next question or finish
    if ($sn < $total) {
        $sn++;
        header("location:welcome.php?q=quiz&step=2&eid=$eid&n=$sn&t=$total");
        exit();
    } else {
        header("location:update.php?q=quiz&step=submit&eid=$eid&t=$total");
        exit();
    }
}

/* -------- submit quiz -------- */
if (@$_GET['q'] == 'quiz' && @$_GET['step'] == 'submit') {
    $eid   = $_GET['eid'];

    // Get user total score (sum of all quiz history entries)
    $scoreData = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(score) AS total FROM history WHERE email='$email'"));
    $totalScore = $scoreData['total'] ?? 0;

    // Update rank table
   // Update rank table
$rankCheck = mysqli_query($con, "SELECT * FROM rank WHERE email='$email'");
if (mysqli_num_rows($rankCheck) == 0) {
    mysqli_query($con, "INSERT INTO rank (email, score, time) VALUES ('$email', '$totalScore', NOW())");
} else {
    mysqli_query($con, "UPDATE rank SET score=$totalScore, time=NOW() WHERE email='$email'");
}


    unset($_SESSION['answers'][$eid]); // Clear answers
    header("location:welcome.php?q=result&eid=$eid");
    exit();
}

/* -------- restart quiz -------- */
if (@$_GET['q'] == 'quizre' && @$_GET['step'] == 25) {
    $eid = $_GET['eid'];
    $t   = $_GET['t'];

    // Remove old history for this quiz only
    mysqli_query($con, "DELETE FROM history WHERE eid='$eid' AND email='$email'");

    // Recalculate rank
    $scoreData = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(score) AS total FROM history WHERE email='$email'"));
    $totalScore = $scoreData['total'] ?? 0;

    mysqli_query($con, "UPDATE rank SET score=$totalScore, time=NOW() WHERE email='$email'");

    unset($_SESSION['answers'][$eid]);
    header("location:welcome.php?q=quiz&step=2&eid=$eid&n=1&t=$t");
    exit();
}
?>
