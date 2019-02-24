<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../objects/listing.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare listing object
$listing = new Listing($db);

// get id of listing to be edited
$data = json_decode(file_get_contents("php://input"));

// set ID property of listing to be edited
$listing->id = $data->id;

// set listing property values
$listing->street = $data->street;
$listing->suburb = $data->suburb;
$listing->list_price = $data->list_price;
$listing->list_date = $data->list_date;
$listing->sale_price = $data->sale_price;
$listing->sale_date = $data->sale_date;

// update the listing
if($listing->update()){

    // set response code - 200 ok
    http_response_code(200);

    // tell the user
    echo json_encode(array("message" => "Listing was updated."));
}

// if unable to update the listing, tell the user
else{

    // set response code - 503 service unavailable
    http_response_code(503);

    // tell the user
    echo json_encode(array("message" => "Unable to update listing."));
}
?>
