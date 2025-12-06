<?php 
include_once 'database.php';
session_start();

// Redirect if not logged in
if(!(isset($_SESSION['email']))){
    header("location:login.php");
    exit();
} else {
  $email = $_SESSION['email'];

$user_query = mysqli_query($con, "SELECT name, email, college FROM user WHERE email='$email' LIMIT 1");
$user_data = mysqli_fetch_assoc($user_query);
$name = $user_data['name'] ?? "User";
$userEmail = $user_data['email'] ?? "N/A";
$college = $user_data['college'] ?? "Not Provided";

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome | Online Quiz System</title>
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f8f9fa; }
    .navbar-brand { font-weight: bold; }
    .panel { background: #fff; border-radius: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 20px; }
    .progress { height: 25px; border-radius: 20px; }
    .progress-bar { font-weight: bold; }
    h1, h3 { font-weight: bold; }
    .table th, .table td { vertical-align: middle; }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#">Online Quiz System</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMenu">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link <?php if(@$_GET['q']==1) echo 'active'; ?>" href="welcome.php?q=1">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if(@$_GET['q']==2) echo 'active'; ?>" href="welcome.php?q=2">History</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if(@$_GET['q']==3) echo 'active'; ?>" href="welcome.php?q=3">Ranking</a>
        </li>
      </ul>

      <ul class="navbar-nav align-items-center">
        <!-- User Dropdown -->
        <li class="nav-item dropdown me-3">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
            <?php echo $name; ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li>
              <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">👤 View Profile</a>
            </li>
          </ul>
        </li>

        <!-- Logout Button -->
        <li class="nav-item">
          <a class="btn btn-danger btn-sm" href="logout.php?q=welcome.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>



<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-primary">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="profileModalLabel">👤 My Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Name:</strong> <?php echo $name; ?></p>
        <p><strong>Email:</strong> <?php echo $userEmail; ?></p>
        <p><strong>College:</strong> <?php echo $college; ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div class="container mt-4">

<!-- Welcome Message -->
<?php
$totalQuiz = mysqli_num_rows(mysqli_query($con, "SELECT * FROM quiz"));
$totalAttempted = mysqli_num_rows(mysqli_query($con, "SELECT DISTINCT eid FROM history WHERE email='$email'"));
$res = mysqli_query($con, "SELECT score FROM rank WHERE email='$email'");
$row = mysqli_fetch_assoc($res);
$overallScore = $row ? $row['score'] : 0;
?>
<div class="alert alert-info text-center">
  <h3>Welcome, <?php echo $name; ?> 👋</h3>
  <p>You attempted <b><?php echo $totalAttempted; ?></b> quizzes out of <b><?php echo $totalQuiz; ?></b>.  
  Your overall score is <b><?php echo $overallScore; ?></b>.</p>
</div>

<!-- Progress -->


<!-- Rank -->
<?php
$rankQuery = mysqli_query($con, "SELECT email FROM rank ORDER BY score DESC");
$rank = 0; $pos = 0;
while ($r = mysqli_fetch_assoc($rankQuery)) {
  $pos++;
  if ($r['email'] == $email) { $rank = $pos; break; }
}
?>
<div class="alert alert-warning text-center">
  🎖️ Your Current Rank: <b><?php echo $rank > 0 ? $rank : "Not ranked yet"; ?></b>
</div>

<!-- Sections -->
<?php 
/* ---------------- Home ---------------- */
if (@$_GET['q']==1) {
  $result = mysqli_query($con,"SELECT * FROM quiz ORDER BY date DESC") or die('Error');
  echo '<div class="panel"><h4>Available Quizzes</h4>
        <div class="table-responsive"><table class="table table-bordered table-striped">
        <thead class="table-dark"><tr>
          <th>#</th><th>Topic</th><th>Total Questions</th><th>Marks</th><th>Action</th>
        </tr></thead><tbody>';
  $c=1;
  while($row = mysqli_fetch_array($result)) {
    $title = $row['title'];
    $total = $row['total'];
    $sahi  = $row['sahi'];
    $eid   = $row['eid'];

    $q12 = mysqli_query($con,"SELECT score FROM history WHERE eid='$eid' AND email='$email'");
    $rowcount = mysqli_num_rows($q12);

    if($rowcount == 0){
      echo '<tr><td>'.$c++.'</td><td>'.$title.'</td><td>'.$total.'</td><td>'.($sahi*$total).'</td>
            <td><a href="welcome.php?q=quiz&step=2&eid='.$eid.'&n=1&t='.$total.'" class="btn btn-success btn-sm">Start</a></td></tr>';
    } else {
      echo '<tr class="table-success"><td>'.$c++.'</td><td>'.$title.' ✅</td><td>'.$total.'</td><td>'.($sahi*$total).'</td>
            <td><a href="update.php?q=quizre&step=25&eid='.$eid.'&n=1&t='.$total.'" class="btn btn-danger btn-sm">Restart</a></td></tr>';
    }
  }
  echo '</tbody></table></div></div>';
}

/* ---------------- Quiz Page ---------------- */
if (@$_GET['q'] == 'quiz' && @$_GET['step'] == 2) {
  $eid   = @$_GET['eid'];
  $sn    = @$_GET['n'];
  $total = @$_GET['t'];

  // progress calculation
  $quizProgress = round(($sn - 1) / $total * 100);
  $progressClass = ($quizProgress >= 100) ? "bg-success" : "bg-info";

  echo '<div class="panel"><h4>Quiz</h4>';

  // Question counter
  echo '<p><b>Question '.$sn.' of '.$total.'</b></p>';

  // Progress bar
  echo '<div class="progress mb-3">
          <div class="progress-bar '.$progressClass.'" role="progressbar" style="width: '.$quizProgress.'%">
            '.$quizProgress.'% Completed
          </div>
        </div>';

  // Fetch current question
  $q = mysqli_query($con, "SELECT * FROM questions WHERE eid='$eid' AND sn='$sn'") or die("Error");
  while ($row = mysqli_fetch_array($q)) {
    $qns = $row['qns'];
    $qid = $row['qid'];
   
  }
 echo '<p><b>Q'.$sn.'. '.$qns.'</b></p>';
  // Get saved answer from session
$savedAns = isset($_SESSION['answers'][$eid][$sn]) ? $_SESSION['answers'][$eid][$sn] : '';

// Fetch options
$q2 = mysqli_query($con, "SELECT * FROM options WHERE qid='$qid'") or die("Error");

echo '<form action="update.php?q=quiz&step=2&eid='.$eid.'&n='.$sn.'&t='.$total.'&qid='.$qid.'" method="POST">';

while ($row = mysqli_fetch_array($q2)) {
    $checked = ($row['optionid'] == $savedAns) ? 'checked' : '';
    echo '<div class="form-check">
            <input class="form-check-input" type="radio" name="ans" value="'.$row['optionid'].'" '.$checked.' required>
            <label class="form-check-label">'.htmlspecialchars($row['option'], ENT_QUOTES, "UTF-8").'</label>
          </div>';
}

echo '<br>';


  // Previous button
  if ($sn > 1) {
    echo '<a href="welcome.php?q=quiz&step=2&eid='.$eid.'&n='.($sn-1).'&t='.$total.'" 
            class="btn btn-dark me-2">Previous</a>';
  }

  // Next button (submit) OR Finish
  if ($sn < $total) {
    echo '<button type="submit" class="btn btn-primary">Next</button>';
  } else {
    echo'<button type="submit" class="btn btn-success">Finish Quiz</button>';

  }

  echo ' <a href="welcome.php?q=1" class="btn btn-danger">Cancel</a>';
  echo '</form></div>';
}



/* ---------------- Result ---------------- */

if (@$_GET['q'] == 'result' && @$_GET['eid']) {
    $eid = $_GET['eid'];
    $email = $_SESSION['email'];

    // Fetch quiz details
    $quizQuery = mysqli_query($con, "SELECT * FROM quiz WHERE eid='$eid'");
    $quizRow = mysqli_fetch_array($quizQuery);
    $total = $quizRow['total'];

    // Fetch latest attempt for this quiz
    $historyQuery = mysqli_query($con, "SELECT * FROM history WHERE email='$email' AND eid='$eid' ORDER BY date DESC LIMIT 1");
    $historyRow = mysqli_fetch_array($historyQuery);

    $score = $historyRow['score'];
    $correct = $historyRow['sahi'];
    $incorrect = $historyRow['wrong'];

    echo '
    <div class="panel">
        <center><h2>📊 Quiz Result</h2></center>
        <table class="table table-bordered">
            <tr><td><b>Total Questions</b></td><td>'.$total.'</td></tr>
            <tr><td><b>Correct Answers</b></td><td>'.$correct.'</td></tr>
            <tr><td><b>Wrong Answers</b></td><td>'.$incorrect.'</td></tr>
            <tr><td><b>Your Score</b></td><td>'.$score.'</td></tr>
        </table>
        <center><a href="welcome.php?q=1" class="btn btn-primary">🏠 Back to Home</a></center>
    </div>';
}



/* ---------------- History ---------------- */
if (@$_GET['q'] == 2) {
  $q = mysqli_query($con,"SELECT * FROM history WHERE email='$email' ORDER BY date DESC");
  echo '<div class="panel"><h4>Quiz History</h4>
        <div class="table-responsive"><table class="table table-bordered table-striped">
        <thead class="table-dark"><tr>
          <th>#</th><th>Quiz</th><th>Questions</th><th>Right</th><th>Wrong</th><th>Score</th>
        </tr></thead><tbody>';
  $c=0;
  while($row = mysqli_fetch_array($q)) {
    $eid   = $row['eid'];
    $score = $row['score'];
    $wrong = $row['wrong'];
    $right = $row['sahi'];
    $done  = $row['level'];
   $q23 = mysqli_query($con,"SELECT title FROM quiz WHERE eid='$eid'");
$title_row = mysqli_fetch_array($q23);
$title = isset($title_row['title']) ? $title_row['title'] : 'Unknown';

    $c++;
    echo '<tr><td>'.$c.'</td><td>'.$title.'</td><td>'.$done.'</td><td>'.$right.'</td><td>'.$wrong.'</td><td>'.$score.'</td></tr>';
  }
  echo '</tbody></table></div></div>';
}

/* ---------------- Ranking ---------------- */
if (@$_GET['q'] == 3) {
  $q = mysqli_query($con,"SELECT * FROM rank ORDER BY score DESC");
  echo '<div class="panel"><h4>Ranking</h4>
        <div class="table-responsive"><table class="table table-bordered table-striped">
        <thead class="table-dark"><tr>
          <th>Rank</th><th>Name</th><th>Email</th><th>Score</th>
        </tr></thead><tbody>';
  $c=0;
  while($row = mysqli_fetch_array($q)) {
    $e = $row['email'];
    $s = $row['score'];
    $user_query = mysqli_query($con, "SELECT name FROM user WHERE email='$e'");
    $u = mysqli_fetch_array($user_query);
    
    if ($u) {
        $c++;
        echo '<tr><td>'.$c.'</td><td>'.$u['name'].'</td><td>'.$e.'</td><td>'.$s.'</td></tr>';
    }
  }
  echo '</tbody></table></div></div>'; // close tags properly
}

?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // enable tooltips
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })
</script>
</body>
</html>
