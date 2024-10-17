<?php
    $conn=mysqli_connect("localhost","root","","myproject");
    if(mysqli_connect_error()){
       echo"<script>alert('can not connect to database');</script>";
     exit();
    }

?>
