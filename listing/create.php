<?php
//required headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorizatopm, X-Requested-With');

// get db connection
include_once '../config/database.php';

// instantiate listing object
include_once '../objects/listing.php';

$database = new Database();
$db = $database->getConnection();

$listing = new Listing($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(
  !empty($data->street) &&
  !empty($data->suburb) &&
  !empty($data->list_price) &&
  !empty($data->list_date)
){
  $listing->street = $data->street;
  $listing->suburb = $data->suburb;
  $listing->list_price = $data->list_price;
  $listing->list_date = $data->list_date;

  // create the listing
  if($listing->create()){

    // set the response code - 201 created
    http_response_code(201);

    // tell the user
    echo json_encode(array('message' => 'Listing was created.'));
  }else{
    //503 - service unavailable
    http_response_code(503);

    echo json_encode(array('message' => 'Unable to create listing.'));
  }
}else{
  // tell user data is incomplete
  // 400 - bad request
  http_response_code(400);

  echo json_encode(array('message' => 'Unable to create listing. Data is incomplete.'));
}
?>
