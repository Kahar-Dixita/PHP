<?php
    include_once 'database.php';
    session_start();
    if(!(isset($_SESSION['email']))){
        header("location:login.php");
    } else {
        $name = $_SESSION['name'];
        $email = $_SESSION['email'];
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Online Quiz System</title>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/font.css">
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style>
        body {
            background: #f4f6f9;
        }
        .sidebar {
            height: 100vh;
            background: #343a40;
            color: white;
            padding-top: 20px;
            position: fixed;
            width: 220px;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 12px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .content {
            margin-left: 230px;
            padding: 20px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.2);
            margin-bottom: 20px;
        }
        .card h3 {
            font-size: 28px;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h3 class="text-center">Admin Panel</h3>
        <p class="text-center">Welcome....</p>
        <hr style="background:white;">
        <a href="dashboard.php?q=0">🏠 Dashboard</a>
        <a href="dashboard.php?q=1">👥 Manage Users</a>
        <a href="dashboard.php?q=2">🏆 Ranking</a>
        <a href="dashboard.php?q=4">➕ Add Quiz</a>
        <a href="dashboard.php?q=5">🗑 Remove Quiz</a>
         <a href="dashboard.php?q=6">👤 Admin Profile</a>
        <a href="logout1.php?q=dashboard.php">🚪 Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <?php if(@$_GET['q']==0){ ?>
            <h2>📊 Dashboard Overview</h2>
            <div class="row">
                <!-- Total Users -->
                <div class="col-md-3">
                    <div class="card text-center panel panel-primary">
                        <div class="panel-heading">Users</div>
                        <div class="panel-body">
                            <?php 
                                $res=mysqli_query($con,"SELECT COUNT(*) as total FROM user");
                                $data=mysqli_fetch_assoc($res);
                                echo "<h3>".$data['total']."</h3>";
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Total Quizzes -->
                <div class="col-md-3">
                    <div class="card text-center panel panel-success">
                        <div class="panel-heading">Quizzes</div>
                        <div class="panel-body">
                            <?php 
                                $res=mysqli_query($con,"SELECT COUNT(*) as total FROM quiz");
                                $data=mysqli_fetch_assoc($res);
                                echo "<h3>".$data['total']."</h3>";
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Total Questions -->
                <div class="col-md-3">
                    <div class="card text-center panel panel-warning">
                        <div class="panel-heading">Questions</div>
                        <div class="panel-body">
                            <?php 
                                $res=mysqli_query($con,"SELECT COUNT(*) as total FROM questions");
                                $data=mysqli_fetch_assoc($res);
                                echo "<h3>".$data['total']."</h3>";
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Ranking Records -->
                <div class="col-md-3">
                    <div class="card text-center panel panel-danger">
                        <div class="panel-heading">Rank Records</div>
                        <div class="panel-body">
                            <?php 
                                $res=mysqli_query($con,"SELECT COUNT(*) as total FROM rank");
                                $data=mysqli_fetch_assoc($res);
                                echo "<h3>".$data['total']."</h3>";
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Quizzes -->
            <div class="panel panel-default">
                <div class="panel-heading">📝 Recent Quizzes</div>
                <div class="panel-body">
                    <ul class="list-group">
                        <?php 
                            $res=mysqli_query($con,"SELECT title,date FROM quiz ORDER BY date DESC LIMIT 5");
                            while($row=mysqli_fetch_assoc($res)){
                                echo "<li class='list-group-item'>".$row['title']." <span class='pull-right'>".$row['date']."</span></li>";
                            }
                        ?>
                    </ul>
                </div>
            </div>
        <?php } ?>

        <!-- Users Page -->
      <?php if(@$_GET['q']==1){ 
    $result = mysqli_query($con,"SELECT * FROM user") or die('Error');
    echo  '<h2>👥 Manage Users</h2>
    <div class="table-responsive"><table class="table table-striped">
    <tr><th>S.N.</th><th>Name</th><th>College</th><th>Email</th><th>Action</th></tr>';
    $c=1;
    while($row = mysqli_fetch_array($result)) {
        $name = $row['name'];
        $email = $row['email'];
        $college = $row['college'];
        echo '<tr><td>'.$c++.'</td><td>'.$name.'</td><td>'.$college.'</td><td>'.$email.'</td>
        <td><a title="Delete User" href="admin_update.php?q=deleteuser&demail='.$email.'" class="btn btn-danger btn-sm">Delete</a></td></tr>';
    }
    echo '</table></div>';
} ?>

        <!-- Ranking Page -->
        <?php if(@$_GET['q']==2){
            $q=mysqli_query($con,"SELECT * FROM rank ORDER BY score DESC") or die('Error');
            echo '<h2>🏆 User Ranking</h2>
            <div class="table-responsive"><table class="table table-bordered">
            <tr style="color:red"><th>Rank</th><th>Email</th><th>Score</th></tr>';
            $c=0;
            while($row=mysqli_fetch_array($q)){
                $e=$row['email'];
                $s=$row['score'];
                $c++;
                echo '<tr><td>'.$c.'</td><td>'.$e.'</td><td>'.$s.'</td></tr>';
            }
            echo '</table></div>';
        } ?>

<?php
// Success / Error messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'success') {
        echo '<div class="alert alert-success text-center">✅ Quiz created successfully! Now add questions.</div>';
    } elseif ($_GET['msg'] == 'error') {
        echo '<div class="alert alert-danger text-center">❌ Something went wrong. Please try again.</div>';
    } elseif ($_GET['msg'] == 'quiz_deleted') {
        echo '<div class="alert alert-warning text-center">🗑️ Quiz deleted successfully!</div>';
    } elseif ($_GET['msg'] == 'pass_success') {
        echo '<div class="alert alert-success text-center">✅ Password updated successfully!</div>';
    } elseif ($_GET['msg'] == 'pass_mismatch') {
        echo '<div class="alert alert-danger text-center">❌ New passwords do not match.</div>';
    } elseif ($_GET['msg'] == 'pass_wrong') {
        echo '<div class="alert alert-danger text-center">❌ Current password is incorrect.</div>';
    }
}

// Add Quiz (Step 1: Create Quiz Form)
if (@$_GET['q'] == 4 && !(@$_GET['step'])) {
?>
    <div class="container mt-4">
        <h2 class="mb-3">➕ Add New Quiz</h2>
        <form class="card p-4 shadow-sm" action="admin_update.php?q=addquiz" method="POST">
            <div class="form-group mb-3">
                <label>Quiz Title</label>
                <input name="name" placeholder="Enter Quiz Title" class="form-control" type="text" required>
            </div>
            <div class="form-group mb-3">
                <label>Total Questions</label>
                <input name="total" placeholder="Total number of questions" class="form-control" type="number" required>
            </div>
            <div class="form-group mb-3">
                <label>Marks per Correct Answer</label>
                <input name="right" placeholder="Marks per correct answer" class="form-control" type="number" required>
            </div>
            <div class="form-group mb-3">
                <label>Minus Marks (Wrong Answer)</label>
                <input name="wrong" placeholder="Minus marks for wrong answer" class="form-control" type="number" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Create Quiz</button>
        </form>
    </div>
<?php
}

// Add Questions (Step 2: Add Questions Form)
if (@$_GET['q'] == 4 && @$_GET['step'] == 2) {
    $n   = @$_GET['n'];
    $eid = @$_GET['eid'];
?>
    <div class="container mt-4">
        <h2 class="mb-3">📝 Add Questions</h2>
        <form class="card p-4 shadow-sm" action="admin_update.php?q=addqns&n=<?php echo $n; ?>&eid=<?php echo $eid; ?>&ch=4" method="POST">
            <?php for ($i=1; $i<=$n; $i++) { ?>
                <div class="border rounded p-3 mb-4">
                    <h5>Question <?php echo $i; ?></h5>
                    <div class="form-group mb-3">
                        <textarea name="qns<?php echo $i; ?>" class="form-control" placeholder="Enter question <?php echo $i; ?>" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input name="<?php echo $i; ?>1" class="form-control" placeholder="Option A" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input name="<?php echo $i; ?>2" class="form-control" placeholder="Option B" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input name="<?php echo $i; ?>3" class="form-control" placeholder="Option C" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input name="<?php echo $i; ?>4" class="form-control" placeholder="Option D" required>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label>Correct Answer</label>
                        <select name="ans<?php echo $i; ?>" class="form-control" required>
                            <option value="a">A</option>
                            <option value="b">B</option>
                            <option value="c">C</option>
                            <option value="d">D</option>
                        </select>
                    </div>
                </div>
            <?php } ?>
            <button type="submit" class="btn btn-success w-100">Save All Questions</button>
        </form>
    </div>
<?php
}


// ---------------- Remove Quiz Page ----------------
if (@$_GET['q'] == 5) {
    $result = mysqli_query($con, "SELECT * FROM quiz ORDER BY date DESC");
?>
    <div class="container mt-4">
        <h2 class="mb-3">🗑️ Remove Quiz</h2>
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Quiz Title</th>
                    <th>Total Questions</th>
                    <th>Marks (Correct)</th>
                    <th>Marks (Wrong)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['total']; ?></td>
                    <td><?php echo $row['sahi']; ?></td>
                    <td><?php echo $row['wrong']; ?></td>
                    <td>
                        <a href="admin_update.php?q=deletequiz&eid=<?php echo $row['eid']; ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Are you sure you want to delete this quiz?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
<?php
}
?>

<?php 
// ---------------- Admin Profile Page ----------------
if (@$_GET['q'] == 6) {
?>
    <div class="container mt-4">
        <h2 class="mb-3">👤 Admin Profile</h2>
        
        <!-- Profile Card -->
        <div class="card p-4 shadow-sm mb-4">
            <h4>Profile Information</h4>
            <p><strong>Name:</strong> <?php echo $name; ?></p>
            <p><strong>Email:</strong> <?php echo $email; ?></p>
        </div>

        <!-- Change Password Form -->
        <div class="card p-4 shadow-sm">
            <h4>Change Password</h4>
            <form action="admin_update.php?q=changepass" method="POST">
                <div class="form-group mb-3">
                    <label>Current Password</label>
                    <input type="password" name="current_password" class="form-control" placeholder="Enter current password" required>
                </div>
                <div class="form-group mb-3">
                    <label>New Password</label>
                    <input type="password" name="new_password" class="form-control" placeholder="Enter new password" required>
                </div>
                <div class="form-group mb-3">
                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Update Password</button>
            </form>
        </div>
    </div>
<?php
}
?>

