<?php
/**
 * Required headers
 */
header("Access-Control-Allow-origin: *");
header("Access-Control-Allow-Headers: Access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow_credentials: true");
header("Content-Type: application/json");

// include database and object files
require_once '../config/Database.php';
require_once '../objects/Product.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

//Instantiate Product object
$product = new Product($db);

// set ID property of record to read
$product->id = isset($_GET["id"]) ? $_GET["id"] : die();

//read the details of product to be edited
$product->readOne();

if($product->name != null) {
    // create array
    $product_arr = array(
        "id" => $product->id,
        "name" => $product->name,
        "description" => $product->description,
        "price" => $product->price,
        "category_id" => $product->category_id,
        "category_name" => $product->category_namee,
    );

    //set response code to - 200 OK
    http_response_code(200);

    //make it json format
    echo json_encode($product_arr);
} else {
    //set Response to - 404 Not found
    http_response_code(404);

    // tell uer that product does not exists
    echo json_encode(array("message" => "Product Not found"));
}