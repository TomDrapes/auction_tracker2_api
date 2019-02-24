<?php
//required headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

//  *** DATABASE CONNECTION ***

// include database and object files
include_once '../config/database.php';
include_once '../objects/listing.php';

//instantiate database and listing object
$database = new Database();
$db = $database->getConnection();

//Initialise object
$listing = new Listing($db);

// *** READ LISTINGS FROM DB ***

// query listings
$stmt = $listing->read();

$num = $stmt->rowCount();

//check if more than 0 record found
if($num > 0){

  //listings array
  $listings_arr = array();
  $listings_arr['records']=array();

  //retrieve table contents
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    //extract row NB: this will make $row['name'] to just $name only
    extract($row);

    $listing_item = array(
      'id' => $id,
      'street' => $street,
      'suburb' => $suburb,
      'list_price' => $list_price,
      'list_date' => $list_date,
      'sale_price' => $sale_price,
      'sale_date' => $sale_date
    );

    array_push($listings_arr['records'], $listing_item);
  }

  // Set response code - 200 OK
  http_response_code(200);

  // show products data in json format
  echo json_encode($listings_arr);
}else{

  // set response code - 404 Not found
  http_response_code(404);

  echo json_encode(
    array('message' => 'No listings found.')
  );
}
?>


