 <?php
 include('connect.php');
// include 'C:\xampp\htdocs\php\MCA_CRUD\config\connect.php';
$id = $_GET['id'];
$delete = "delete from emp where id= $id";
if(mysqli_query($conn, $delete)){
    header("Location:dash.php");
    exit();

}else{
    echo "error";
}



?> 