<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';

// instantiate alumno object
include_once '../objects/cortez.php';

$database = new Database();
$db = $database->getConnection();
 //new comment 
$cortez = new Cortez($db);

// get posted data
$timestamp = date('Y-m-d G:i:s');
$data = json_decode(file_get_contents("php://input"));


// set alumno property values
$cortez->total_efectivo = $data->total_efectivo;
$cortez->total_pos = $data->total_pos;
$cortez->total_compras = $data->total_compras;
$cortez->fechatiempo = $timestamp;

// create the alumno
if($cortez->create()){
    echo '{';
        echo '"message": "cortez was created."';
    echo '}';
}

// if unable to create the alumno, tell the user
else{
    echo '{';
        echo '"message": "Unable to create cortez."';
    echo '}';
}
?>