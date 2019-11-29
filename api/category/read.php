<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and objects
require_once '../config/Database.php';
require_once '../objects/Product.php';

#instantiate database and category objects
$database = new Database();
$db = $database->getConnection();

#instantiate object
$category = new Category($db);

#query Categories
$stmt = $category->read();
$num = $stmt->rowCount();

#check if there is more than 0 categories
if($num > 0) {
    // products array
    $categories_arr = array();
    $categories_arr['records'] = array();

    #Retrieve table contents
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        extract($row);

        $category_item = array(
            "id" => $id,
            "name" => $name,
            "description" => htmlspecialchars($description)
        );

        array_push($categories_arr['records'], $category_item);
    }
    // set response to - 200 OK
    http_response_code(200);

    #Tell the user
    echo json_encode($categories_arr);
} else {
    // set response code to - 404 Not found
    http_response_code(404);

    #Tell user
    echo json_encode(array("message" => "No categories Found"));
}