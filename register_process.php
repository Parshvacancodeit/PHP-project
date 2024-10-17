
<?php require( 'connect.php');
    if(isset($_POST['register'])){
        $user_registered_query="SELECT * FROM `registered_user` WHERE `username`='$_POST[username]' OR `email`= '$_POST[email]'";
        $result = mysqli_query($conn,$user_registered_query);
    }
    if($result){ 
        if(mysqli_num_rows($result)>0){
            $result_fetch=mysqli_fetch_assoc($result);
            if($result_fetch['username']==$_POST['username']){
                echo
                "<script>
                alert('username-already taken');
                window.location.href='index.php';
                </script>";
            }
            else{
                echo
                "<script>
                alert('email-already taken');
                window.location.href='index.php';
                </script>";
            }
        }
        else{
            $password=password_hash($_POST['password'],PASSWORD_BCRYPT);
            $query="INSERT INTO `registered_user`(`username`, `email`, `password`) VALUES ('$_POST[username]','$_POST[email]','$password');";
            if(mysqli_query($conn,$query)){
                echo
                "<script>
                alert('registration successful');
                window.location.href='index.php';
                </script>";
            }
            else{
                echo
                "<script>
                alert('email-already taken');
                window.location.href='index.php';
                </script>";
            }
        }

    }
    else{
        echo"<script>alert('can not run query');</script>";
    }
    
?>
