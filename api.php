<?php
require("./db.php");
if($_REQUEST['fn'])
{
    switch($_REQUEST['fn'])
    {
        case "createAppt":
            $sql = "INSERT INTO appointments (`cid`, `items`, `date`, `duration`, `status`, `eid`) VALUES ('".$DB->real_escape_string($_REQUEST['cid'])."','".$DB->real_escape_string($_REQUEST['items'])."','".$DB->real_escape_string($_REQUEST['date'])."','".$DB->real_escape_string($_REQUEST['duration'])."','".$DB->real_escape_string($_REQUEST['status'])."','".$DB->real_escape_string($_REQUEST['eid'])."')";
            print($sql);
            $DB->query($sql) or die ($DB->error);
            exit(1);
            break;
        case "createCustomer":
            $sql = "INSERT INTO customers (`fname`, `lname`, `email`, `phone`) VALUES ('".$DB->real_escape_string($_REQUEST['fname'])."','".$DB->real_escape_string($_REQUEST['lname'])."','".$DB->real_escape_string($_REQUEST['email'])."','".$DB->real_escape_string($_REQUEST['phone'])."')";
            $qry = $DB->query($sql) or die ($DB->error);
            exit('1');
            break;
        case "createService":
            $sql = "INSERT INTO services (`name`, `price`, `duration`) VALUES ('".$DB->real_escape_string($_REQUEST['name'])."','".$DB->real_escape_string($_REQUEST['price'])."','".$DB->real_escape_string($_REQUEST['duration'])."')";
            $qry = $DB->query($sql) or die ($DB->error);
            exit('1');
            break;
        case "getAppts":
            $sql = "SELECT * FROM appointments WHERE `status` = 1 and `date` >= '".$DB->real_escape_string($_REQUEST['startdate'])."' and `date` <= '".$DB->real_escape_string($_REQUEST['enddate'])."' ORDER BY `date` asc";
            $qry = $DB->query($sql);
            $res = [];
            while($result = $qry->fetch_assoc())
            {
                array_push($res, $result);
            }
            print(json_encode($res));
            break;
        case "getCusts":
            $sql = "SELECT * FROM customers WHERE active = 1";
            $qry = $DB->query($sql);
            $res = [];
            while($result = $qry->fetch_assoc())
            {
                array_push($res, $result);
            }
            print(json_encode($res));
            break;
        case "getServs":
            $sql = "SELECT * FROM services WHERE active = 1";
            $qry = $DB->query($sql);
            $res = [];
            while($result = $qry->fetch_assoc())
            {
                array_push($res, $result);
            }
            print(json_encode($res));
            break;
        case "updateAppt":
            $val_sql = "";
            $key = explode(",",$_REQUEST['key']);
            $val = explode(",",$_REQUEST['val']);
            for($i=0; $i < count($key); $i++)
            {
                $val_sql .= "`".$DB->real_escape_string($key[$i])."` = '".$DB->real_escape_string($val[$i])."',";
            }
            $val_sql = substr($val_sql, 0, -1);
            $sql = "UPDATE appointments SET ".$val_sql." WHERE `id` = '".$DB->real_escape_string($_REQUEST['appt_id'])."'";
            $qry = $DB->query($sql) or die ($DB->error . "\n" . $sql);
            exit('1');
            break;
        case "login":
            $sql = "SELECT * FROM users WHERE `username` = '".$DB->real_escape_string($_REQUEST['username'])."' AND `password` = '".$DB->real_escape_string($_REQUEST['password'])."'";
            $qry = $DB->query($sql);
            if($qry->num_rows == 1)
            {
                $res = $qry->fetch_assoc();
                $sql = "UPDATE users SET `ssid` = '".$ssid."' WHERE `uid` = '".$res['uid']."'";
                $qry = $DB->query($sql);
                print(1);
                exit();
            }
            else
            {
                exit(0);
            }
            break;
        case "logout":
            $sql = "UPDATE users SET `ssid` = '8675309' WHERE `ssid` = '".$ssid."'";
            $qry = $DB->query($sql);
            session_destroy();
            exit(1);
            break;
        case "test":
            print("The Test was successful");
            break;
    }
} else {
    exit();
}
?>