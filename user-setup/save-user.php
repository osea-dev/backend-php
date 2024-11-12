<?php
	header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type,x-prototype-version,x-requested-with');
    require_once('../inc/database.php');
	$data = file_get_contents("php://input");
	//$data = json_encode(array(array("earningCode" => 1,"earningName" => 'One1')));
	$objData = json_decode($data);
	$database = $objData->database;
	$db->selectdb($database);
	try {
		if(count($objData->data) <= 0 )
		{
			$result["code"] = 2;
			$result["message"] = "No Record to save.";
			echo json_encode($result);
			return;
		}
		
		// $option = $objData->mode;
		$data = $objData->data;
		
		$userid = $data->userid;
		$username = $data->username;
		//$password = $data->password;
		$password = f_endecrypt($data->password,$username,'e');
		
		$db->beginTrans();

        $rs = $db->query("select *  from `users` where username = '$username'");
        if ($db->nrows($rs) > 0) {
            $result["code"] = 3;
            $result["message"] = "Username Already Exists.";
            echo json_encode($result);
            return;
            
        }
        $rs->close();
    
        $rs = $db->query("select IFNULL(MAX(`userid`),0) + 1 as `userid`  from `users`");
        if ($db->nrows($rs) > 0) {
            $row = $db->fassoc($rs);
            $userid = $row['userid'];
        }
        else { 
            $userid = 1;
        }
        $rs->close();
        
        $query1 = "INSERT INTO `users` (`userid`,`username`,`password`) VALUES($userid,'$username','$password')";
        $result["code"] = 0;
        $result["message"] = "Record Successfully Added.";
		
		$db->commitTrans();
		
		echo json_encode($result);
	}
	catch(Exception $e) {
		$result["code"] = 999;
		$result["message"] = $e->getMessage();
		echo json_encode($result);
	}
?>