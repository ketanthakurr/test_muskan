<?php
include("db.php");

if(isset($_POST['submit']))
{
    $Name=$_POST['Name'];
    $DeviceNo=$_POST['DeviceNo'];
    $UserId=$_POST['UserId'];
    $DeviceId=$_POST['DeviceId'];
    $PrimaryDevice=($_POST["PrimaryDevice"] === "yes") ? 1 : 0;;

    $sql = "INSERT into ap_devices (Name, DeviceNo, UserId, DeviceId, PrimaryDevice) values ( '$Name','$DeviceNo', '$UserId','$DeviceId','$PrimaryDevice')";
    $result = mysqli_query($con,$sql); 

    if($result)
    {
        echo"Sucess";
    }
    else{
        echo "Failed";
    }
}

?>