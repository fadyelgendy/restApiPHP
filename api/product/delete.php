<?php
//required Headers
header("Access-Cont-Allow-Origin: *");
header("Content-Type: application/json, charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-MAx-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include Database and objects
require_once '../config/Database.php';
require_once '../objects/Product.php';

//Create Connection
$database = new Database();
$db = $database->getConnection();

//Instantiate Object product
$product = new Product($db);

//Get product id
$data = json_decode("php://input");

//Set product id to be deleted
$product->id = $data->id;

//Delete Product
if($product->delete()){
    // set response to - 200 OK
    http_response_code(200);

    //tell the user
    echo json_encode(array("message" => "product deleted Successfully"));
} else {
    // set response to - 503 service unavailable
    http_response_code(503);

//    Tell the user
    echo json_encode(array("message" => "unable to delete  product"));
}