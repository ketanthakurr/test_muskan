<?php
include("db.php");

if(isset($_POST['submit']))
{
    $UserId=$_POST['UserId'];
    $Email=$_POST['Email'];
    $Password=$_POST['Password'];
    $Name=$_POST['Name'];
    $Mobile=$_POST['Mobile'];

    $sql = "INSERT into fw_users (UserId, Email, Password, Name, Mobile) values ( '$UserId','$Email', '$Password', '$Name', '$Mobile')";
    $result = mysqli_query($con,$sql); 

    if($result)
    {
        echo"Success";
    }
    else{
        echo "Failed";
    }
    echo "<meta http-equiv='refresh' content='0'>";
}

?>