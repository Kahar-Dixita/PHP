<?php
include('connect.php');


if(isset($_POST['submit']))
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $gender = $_POST['gender'];
    $course = $_POST['course'];
    $dob =$_POST['dob'];
    $address = $_POST['address'];
    $file_name = $_FILES['file']['name'];
    $tmp_name = $_FILES['file']['tmp_name'];
    $upload_path = "../uploads/".basename($file_name);
    move_uploaded_file($tmp_name, $upload_path);
    




$sql = "INSERT INTO emp(id,name,email,contact,gender,course,dob,address,file)value(null,'$name','$email','$contact','$gender','$course','$dob','$address', '$file_name')";
if(mysqli_query($conn,$sql)){
    echo "inserted successfully";
    header("Location:dash.php");
    }else {
        echo "Error: " . mysqli_error($conn);
    }
}
// echo "hello";
?>
