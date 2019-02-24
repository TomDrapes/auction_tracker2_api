<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
//include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/listing.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$listing = new Listing($db);

// get keywords
$keywords=isset($_GET["s"]) ? $_GET["s"] : "";

// query listings
$stmt = $listing->search($keywords);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

    // listings array
    $listings_arr=array();
    $listings_arr["records"]=array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        $listing_item=array(
            "id" => $id,
            "street" => $street,
            "suburb" => $suburb,
            "list_price" => $list_price,
            "list_date" => $list_date,
            "sale_price" => $sale_price,
            "sale_date" => $sale_date
        );

        array_push($listings_arr["records"], $listing_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show listings data
    echo json_encode($listings_arr);
}

else{
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no listings found
    echo json_encode(
        array("message" => "No listings found.")
    );
}
?>
