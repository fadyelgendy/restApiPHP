<?php
//required header
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Aloow-Method: POST");
header("Access-Controll-Max-Age: 3600");
header("Access-Control-Aloow-Header: Content-Type, Access-Control, Authorization, X-Request-Width");

/**
 * Get database connection
 */
 require_once '../config/Database.php';

 /**
  * Instantiate product Object
  */
 require_once '../objects/Product.php';

 $database = new Database();
 $db = $database->getConnection();

 $product = new Product($db);

 /**
  * Get Posted Data
  */

 $data = json_decode(file_get_contents("php://input"));

 /**
  * Make sure data is not empty
  */
 if(
     !empty($data->name) &&
     !empty($data->price) &&
     !empty($data->description) &&
     !empty($data->category_id)
 ) {
     //set product properties values
     $product->name = $data->name;
     $product->price = $data->price;
     $product->description = $data->decription;
     $product->category_id = $data->category_id;
     $product->created = date('Y-m-d H:i:s');

     // Create the product
     if($product->create()) {
         //set status code - 201 Created
         http_response_code(201);
         // tell the user
         echo json_encode(array("message"=> "Product Created"));
     } else {
         // if unable to create the products tell the user
         // set response code - 503 service unavailable
         http_response_code(503);

         //tel the user
         echo json_encode(array("message" => "Unable to create the product"));
     }
     // tell the user the data is not complete
 } else {
     //set response code - 400 Bad request
    http_response_code(400);

    echo json_encode(array("Message" => "Unabel to create the product"));
 }