<?php require('connect.php');
require('register_process.php');

session_start();
if(isset($_POST['login'])){
    $query= " SELECT * FROM `registered_user` WHERE `email`='$_POST[email_username]'OR `username`='$_POST[email_username]'";
    $result=mysqli_query($conn,$query);

    if($result){
        if(mysqli_num_rows($result)==1){
            $result_fetch=mysqli_fetch_assoc($result);
            if(password_verify($_POST['password'],$result_fetch['password'])){
                $_SESSION['logged-in']=true;
                $_SESSION['username']=$result_fetch['username'];
                $_SESSION['email']=$result_fetch['email'];
                header("location: index.php");
            }
            else{
                echo
                "<script>
                alert('Incorrect Password');
                window.location.href='index.php';
                </script>";
            }
        }
        else{
            echo
        "<script>
        alert('User not found');a
        window.location.href='index.php';
        </script>";
        }

    }
    else{
        echo
        "<script>
        alert('Cannot Run Query');
        window.location.href='index.php';
        </script>";
    }
}
else{
    echo"<script>alert('can not run query');</script>";
}


 ?>
