<?php
//required headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: access');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

//include db and object files
include_once '../config/database.php';
include_once '../objects/listing.php';

$database = new Database();
$db = $database->getConnection();

//prepare listing object
$listing = new Listing($db);

//set ID property of record to read
$listing->id = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of listing to be edited
$listing->readOne();

if($listing->street != null){
  //create array
  $listing_arr = array(
    'id' => $listing->id,
    'street' => $listing->street,
    'suburb' => $listing->suburb,
    'list_price' => $listing->list_price,
    'list_date' => $listing->list_date,
    'sale_price' => $listing->sale_price,
    'sale_date' => $listing->sale_date
  );

  // 200 OK
  http_response_code(200);

  echo json_encode($listing_arr);
} else {
  // 404 Not Found
  http_response_code(404);

  echo json_encode(array('message' => 'Listing does not exist.'));
}
?>
