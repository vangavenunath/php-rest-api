<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare user object
$user = new User($db);
  
// set ID property of record to read
$user->username = isset($_GET['username']) ? $_GET['username'] : die();
  
// read the details of user to be edited
$user->readOne();
  
if($user->username!=null){
    // create array
    $user_arr = array(
        "username" => $user->username,
        "password" => $user->password,
        "last_updated" => $user->last_updated
    );
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($user_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user user does not exist
    echo json_encode(array("message" => "user does not exist."));
}
?>