<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap-5.1.0-dist/css/bootstrap.min.css">
    <script src="../bootstrap-5.1.0-dist/js/bootstrap.min.js"></script>
    <title>Dashboard</title>
</head>
<body class="p-5 m-5">
   <a href="formdata.html"><button type="button" class="btn btn-primary">Add new </button></a>
   <a href="../q_3/logout.php" class="btn btn-primary" style="float:right"; >logout</a>

<table class="table table-striped">
    
 <thead>
     <tr>
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <th scope="col">Email</th>
        <th scope="col">Contact</th>
        <th scope="col">Gender</th>
        <th scope="col">Course</th>
        <th scope="col">DOB</th>
        <th scope="col">Address</th>
        <th scope="col">File</th>
        <th></th>
        <th></th>
    </tr>
  </thead>

  <tbody>
    <?php
        include('connect.php');
        $select = "select * from emp";
        $result = mysqli_query($conn,$select);
        if(mysqli_num_rows($result)>0){
            while($row=mysqli_fetch_assoc($result)){

         
    ?>
    <tr>
        <th scope="row"><?php echo $row['id'] ?></th>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['email'];?></td>
        <td><?php echo $row['contact'];?></td>
        <td><?php echo $row['gender'];?></td>
        <td><?php echo $row['course'];?></td>
        <td><?php echo $row['dob'];?></td>
        <td><?php echo $row['address'];?></td> 
        <td>
                     <?php if ($row['file']): ?>
                        <img src="../uploads/<?= $row['file'] ?>" alt="photo" width="80" height="60">
                    <?php else: ?>
                        No Photo
                    <?php endif; ?>
        </td>
        
        <td> 
       <a href="update.php?id=<?php echo $row['id']; ?>"><button class="btn btn-danger">UPDATE</button></a>
        <a href="delete.php?id=<?php echo $row['id']; ?>"><button type="button" class="btn btn-secondary">Delete</button></a>
        </td>
    </tr>
    <?php
       }
    }
    ?>
       
  </tbody>
</table>


</body>
</html>