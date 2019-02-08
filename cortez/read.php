<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/cortez.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$cortez = new Cortez($db);
 
// query products
$stmt = $cortez->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // products array
    $actividad_arr=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $actividad_item=array(
            "ID_cortez" => $ID_cortez,
            "total_efectivo" => $total_efectivo,
            "total_pos" => $total_pos,
            "total_compras" => $total_compras,
            "fechatiempo" => $fechatiempo
        );
 
        array_push($actividad_arr, $actividad_item);
    }
 
    echo json_encode($actividad_arr);
}
 
else{
    echo json_encode(
        array("message" => "No products found.")
    );
}
?>