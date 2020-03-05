<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, append,delete,entries,foreach,get,has,keys,set,values");
  
// include database and object files
include_once './config/database.php';
include_once './objects/user.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare user object
$user = new User($db);
  
// get username of user to be edited
$data = json_decode(file_get_contents("php://input"));
  
// set username property of user to be edited
$user->username = $data->username;
  
// set user property values
$user->password = $data->password;

// query users
$stmt = $user->check_user();
$num = $stmt->rowCount();

if($num>0){
  
    // users array
    $users_arr=array();
  
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $users_arr=array(
            "message" => ""
        );
      }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show users data in json format
    echo json_encode($users_arr);
}
else{
  
    // set response code - 404 Not found
    http_response_code(200);
  
    // tell the user no products found
    echo json_encode(
        array("message" => "Invalid username or password")
    );
}
?>