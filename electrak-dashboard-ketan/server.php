<?php
function OpenCon()
{
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $db = "electrak";
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Connect failed: %s\n" . $conn->error);

    return $conn;
}

function CloseCon($conn)
{
    $conn->close();
}

function getUsersData()
{
    $conn = OpenCon();

    $sql = "SELECT * FROM fw_users WHERE InActive = 0";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = array();

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        CloseCon($conn);

        return $data;
    } else {
        return array();
    }
}


function getDeviceData()
{

    $conn = OpenCon();
    $sql = "SELECT * FROM ap_devicelog WHERE InActive = 0";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = array();

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        CloseCon($conn);

        return $data;
    } else {
        return array();
    }
}
