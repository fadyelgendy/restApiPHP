<?php
//required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTD-8");

// connection to database
/**
 * include database and object files
 */
 require '../config/Database.php';
 require '../objects/Product.php';

 /**
  * Instantiate database and product object
  */

  $database = new Database();
  $db = $database->getConnection();

  // Initialize object
  $product = new Product($db);

  // Query Products
  $stmt = $product->read();
  $num = $stmt->rowCount();

// check if more than record found
if($num > 0) {
    // products array
    $products_arr = array();
    $products_arr["records"] = array();

    // retrieve out tables content, fetch() is faster than fetchAll()
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // Extract row , make $row["name"] just $name.
        extract($row);
        $products_item = array(
            "id" => $id,
            "name" => $name,
            "description" => $description,
            "price" => $price,
            "category_id" => $category_id,
            "category_name" => $category_name,
        );
        array_push($products_arr["records"], $products_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    //show product data in json format

} else {
    //set response code - 404 Not Found
    http_response_code(404);

    echo json_encode(
        array("message" => "No Products Found")
    );
}