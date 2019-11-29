<?php
//required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json, charset=UTF-8");

// include database and product Object
require_once '../config/Database.php';
require_once '../objects/Product.php';

//Instantiate database and product object
$database = new Database();
$db = $database->getConnection();

//initialize object
$product = new Product($db);

//Get keyWords
$keywords = isset($_GET["s"]) ? $_GET['s'] : "";

// query products
$stmt = $product->search($keywords);
$num = $stmt->rowCount();

// check if there is more than 0 records
if($num > 0 ){
    //Products array
    $products_arr = array();
    $products_arr["records"] = array();

    //Retrieve our table contents
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Extract row
        extract($row);

        $product_item = array(
            "id" => $id,
            "name" => $name,
            "description" => $description,
            "price" => $price,
            "category_id" => $category_id,
            "category_name" => $category_name
        );

        // bind item to product array
        array_push($products_arr['records'], $product_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    //Show data
    echo json_encode($products_arr);
} else {
    // set response code - 404 Not Found
    http_response_code(404);

    // tell the user no data found
    echo json_encode(array("message" => "No products Found"));
}