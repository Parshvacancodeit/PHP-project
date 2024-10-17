<?php
  include('../connect.php');
  if(isset($_GET['id']))
  {
  $id=$_GET['id'];
  $query1=mysqli_query($conn,"delete from inventory where id='$id'");
  if($query1)
  {
  header('location:inventory.php');
  }
  }
  ?>