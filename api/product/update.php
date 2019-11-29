<?php
    //require header
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json, charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Header, Authorization, X-Requested-With");

    /**
     * Include Database And object files
     */

    require_once '../config/Database.php';
    require_once '../objects/Product.php';

    /**
     * Get database connection
     */
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate product Object
    $product = new Product($db);

    // Get id property of product to be edited
    $data = json_decode(file_get_contents("php://input"));

    // Set id property of id to be edited
    $product->id = $data->id;

    //set product property values
    $product->name = $data->name;
    $product->price = $data->price;
    $product->description = $data->description;
    $product->category_id = $data->categoru_id;

    //Update the product
    if($product->update()){
        //set response to - 200 OK
        http_response_code(200);

        //tell the user
        echo json_encode(array("message" => "Product updated Successful"));
    } else {
        //Set response to - 503 Service unavailable
        http_response_code(503);

        //Tell rhe user
        echo json_encode(array("message" => "Unable to update the product"));

    }