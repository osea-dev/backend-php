<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type,x-prototype-version,x-requested-with');
include_once('inc/database.php');
$data = file_get_contents("php://input");
$objData = json_decode($data);
$user = array();
$menulist = array();
// echo $_SESSION['database'];
$username = $db->fescape($objData->username);
$password = f_endecrypt($objData->password,$username,'e');
$database = $objData->database;
$db->selectdb($database);

$query = "SELECT userid, username, password
          FROM users
          WHERE username = '$username'";

$rs = $db->query($query);

if ($db->nrows($rs) > 0) {
    $row = $db->fassoc($rs);
    $userPassword = $row['password'];

    if ($userPassword == f_endecrypt($password, $username, 'd')) {
        // Successful login
        $user['isloggedIn'] = true;
        $user['userId'] = $row['userid'];
        $user['username'] = $username;
        $user['database'] = $database;
        $user['password'] = $password;
    } else {
        // Incorrect password
        $user['isloggedIn'] = false;
        $user['loginStatus'] = 'Invalid Username or Password.';
    }
} else {
    // Username not found
    $user['isloggedIn'] = false;
    $user['loginStatus'] = 'Invalid Username.';
}

echo json_encode($user);

?>