<?php
//require headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once '../objects/listing.php';

// utilities
$utilities = new Utilities();

// instantiate db and listing object
$database = new Database();
$db = $database->getConnection();

// initialise object
$listing = new Listing($db);

// query listings
$stmt = $listing->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// check if more than 0 records found
if($num>0){
  //listings array
  $listings_arr=array();
  $listings_arr['records']=array();
  $listings_arr['paging']=array();

  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
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

  // include paging
  $total_rows = $listing->count();
  $page_url = "{$home_url}product/read_paging.php?";
  $paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
  $listings_arr['paging'] = $paging;

  // 200 OK
  http_response_code(200);

  echo json_encode($listings_arr);
} else {
  // 404 Not Found
  http_response_code(404);

  echo json_encode(array('message' => 'No listings found.'));
}
?>
