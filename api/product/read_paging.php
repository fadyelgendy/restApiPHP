<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json, charset=UTF-8");

// include database and object
require_once '../config/Core.php';
require_once '../config/Database.php';
require_once '../objects/Product.php';

// Utilities
$utilities = new Utilities();

//Instantiate database and object
$database = new Database();
$db = $database->getConnection();

//Initialize product
$product = new Product($db);

// Query products
$stmt = $product->readPaging($from_record_num, $record_per_page);
$num = $stmt->rowCount();

// check if there mor than 0 records
if($num > 0){
    // product array
    $products_arr = array();
    $products_arr["records"] = array();
    $products_arr["paging"] = array();

    // retrieve products table
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Extract data
        extract($row);

        $product_item = array(
            "id" => $id,
            "name" => $name,
            "description" => $description,
            "price" => $price,
            "category_id" => $category_id,
            "category_name" => $category_name
        );

        array_push($products_arr['records'], $product_item);
    }

    //include paging
    $total_rows = $product->count();
    $page_url = "{$home_url}product/read_paging.php?";
    $paging = $utilities->getPaging($page, $total_rows, $record_per_page, $page_url);
    $products_arr["paging"] = $paging;

    //set response code - 200 OK
    http_response_code(200);

    //make it json format
    echo json_encode($products_arr);
} else {
//    set response to - 404 not found
    http_response_code(404);

    //tell user products not found
    echo json_encode(array("message" => "Products not found"));
}