<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/cortez.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare product object
$cortez = new Cortez($db);
 
// get id of product to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of product to be edited
$cortez->ID_cortez = $data->ID_cortez;
 
// set product property values
$cortez->total_efectivo = $data->total_efectivo;
$cortez->total_pos = $data->total_pos;
$cortez->total_compras = $data->total_compras;
 
// update the product
if($cortez->update()){
    echo '{';
        echo '"message": "Cortez was updated."';
    echo '}';
}
 
// if unable to update the product, tell the user
else{
    echo '{';
        echo '"message": "Unable to update Cortez."';
    echo '}';
}
?>