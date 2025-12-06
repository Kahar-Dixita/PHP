<?php
include('connect.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap-5.1.0-dist/css/bootstrap.min.css">
    <script src="../bootstrap-5.1.0-dist/js/bootstrap.min.js"></script>
    <title>Registration Form</title>
</head>

<?php


$select = "select*from emp where id= $_GET[id]";
$result = mysqli_query($conn, $select);
$row = mysqli_fetch_assoc($result);


if(isset($_POST['submit']))
{
    $id = $_GET['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $gender = $_POST['gender'];
    $course = $_POST['course'];
    $dob =$_POST['dob'];
    $address = $_POST['address'];

    $file_name = $_FILES['file']['name'];
    $tmp_name = $_FILES['file']['tmp_name'];
    $upload_path = "../uploads/" . basename($file_name);


    $update = "UPDATE emp SET name='$name', email='$email', contact='$contact', gender='$gender',
                course='$course', dob='$dob', address='$address', file='$file_name' WHERE id=$id";

    if (mysqli_query($conn, $update)) {
        header("Location:dash.php");
        exit(); // Very important!
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}


?>

<body class="col-sm-7 p-5" style="margin-left: 15%;">
   
    <h1 class="text-center">Registration Form</h1>
    <form method="post" class="form-control p-5 bg-secondary" enctype="multipart/form-data">
        <div class="row">
            <div class="col">
                <div class="mb-3">
                    <label for="name" class="form-label">Name :</label>
                    <input type="text" class="form-control" value="<?php echo $row['name'] ?>" name="name" id="name" placeholder="Enter Name" required>
                </div>
            </div>

            <div class="col">
                <div class="mb-3">
                    <label for="email" class="form-label">Email :</label>
                    <input type="email" class="form-control" value="<?php echo $row['email'] ?>" name="email" id="email" placeholder="Enter Email" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="mb-3">
                    <label for="contact" class="form-label">Contact</label>
                    <input type="text" class="form-control" value="<?php echo $row['contact'] ?>"name="contact" id="contact"
                        placeholder="Enter Conatact" required></textarea>
                </div>
            </div>

           <div class="col">
                <div class="form-check">
                    <label for="gender" class="form-label">Gender :</label><br>
                    <input class="form-check-input" type="radio"  value ="male"name="gender"  <?php if($row['gender'] =='male') echo 'checked';?> id="male" required>
                    <label class="form-check-label" for="gender">
                        Male
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio"  value ="female" name="gender"  <?php if($row['gender'] =='female') echo 'checked';?> id="female" required>
                    <label class="form-check-label" for="gender">
                        Female
                    </label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <label for="course" class="form-label">Course :</label><br>
               <select class="form-select" name="course" required>
                  <option disabled>Select</option>
                  <option value="php" <?php if ($row['course'] == 'php') echo 'selected'; ?>>PHP</option>
                  <option value="fullstack" <?php if ($row['course'] == 'fullstack') echo 'selected'; ?>>Fullstack</option>
                  <option value="c++" <?php if ($row['course'] == 'c++') echo 'selected'; ?>>C++</option>
</select>

            </div>

            <div class="col">
                <div class="mb-3">
                    <label for="dob" class="form-label">DOB :</label>
                    <input type="date" class="form-control" value="<?php echo $row['dob'] ?>" name="dob" id="dob" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name ="address" rows="3" placeholder="Enter Some Message.." required><?php echo $row['address']; ?></textarea>
                </div>
            </div>

            <div class="col">
                <div class="mb-3">
                    <label for="file" class="form-label">Upload File :</label>
                   <input type="file" class="form-control" name="file" id="file">
                    <?php if ($row['file']) : ?>
                   <p>Current file: <a href="../uploads/<?php echo $row['file']; ?>" target="_blank"><?php echo $row['file']; ?></a></p>
                    <?php endif; ?>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
              <button type="submit" class="btn btn-primary" name="submit" value="submit">Submit</button>
            </div>

             <div class="col">
              <a href="dash.php" class="btn btn-primary">Cancel</a>

            </div>
        </div>
    </form>
</html>
