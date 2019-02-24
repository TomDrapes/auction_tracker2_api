<?php
class Listing{

  //db connection and table street
  private $conn;
  private $table_name = 'listings';

  //object properties
  public $street;
  public $suburb;
  public $list_price;
  public $list_date;
  public $sale_price;
  public $sale_date;

  //Constructor wit $db as db connection
  public function __construct($db){
    $this->conn = $db;
  }

  //read listings
  function read(){

    echo $this->table_name;
    //select all query
    $query = "SELECT * FROM " . $this->table_name;

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    //execute query
    $stmt->execute();

    return $stmt;
  }

  // Create listing
  function create() {

    //query to insert record
    $query = 'INSERT INTO ' . $this->table_name . ' SET street = :street, suburb = :suburb, list_price = :list_price, list_date = :list_date';

    //prepare query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->street=htmlspecialchars(strip_tags($this->street));
    $this->suburb=htmlspecialchars(strip_tags($this->suburb));
    $this->list_price=htmlspecialchars(strip_tags($this->list_price));
    $this->list_date=htmlspecialchars(strip_tags($this->list_date));
    $this->sale_price=htmlspecialchars(strip_tags($this->sale_price));
    $this->sale_date=htmlspecialchars(strip_tags($this->sale_date));

    //bind values
    $stmt->bindParam(':street', $this->street);
    $stmt->bindParam(':suburb', $this->suburb);
    $stmt->bindParam(':list_price', $this->list_price);
    $stmt->bindParam(':list_date', $this->list_date);

    // execute query
    if($stmt->execute()){
      return true;
    }
    return false;
  }

  function readOne(){
    // query tp read single record
    $query = 'SELECT * FROM ' . $this->table_name . ' WHERE id = ? LIMIT 0,1';

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    //bind id of listing to be updated
    $stmt->bindParam(1, $this->id);


    //execute query
    $stmt->execute();

    //get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    //set values to object properties
    $this->street = $row['street'];
    $this->suburb = $row['suburb'];
    $this->list_price = $row['list_price'];
    $this->list_date = $row['list_date'];
    $this->sale_price = $row['sale_price'];
    $this->sale_date = $row['sale_date'];
  }

  // update the product
  function update(){

    // update query
    $query = 'UPDATE '. $this->table_name .'
      SET
        street = :street,
        suburb = :suburb,
        list_price = :list_price,
        list_date = :list_date,
        sale_price = :sale_price,
        sale_date = :sale_date
      WHERE id = :id';

    echo $this->sale_price .' '. $this->sale_date;

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->street=htmlspecialchars(strip_tags($this->street));
    $this->suburb=htmlspecialchars(strip_tags($this->suburb));
    $this->list_price=htmlspecialchars(strip_tags($this->list_price));
    $this->list_date=htmlspecialchars(strip_tags($this->list_date));
    $this->sale_price=htmlspecialchars(strip_tags($this->sale_price));
    $this->sale_date=htmlspecialchars(strip_tags($this->sale_date));
    $this->id=htmlspecialchars(strip_tags($this->id));


    // bind new values
    $stmt->bindParam(':street', $this->street);
    $stmt->bindParam(':suburb', $this->suburb);
    $stmt->bindParam(':list_price', $this->list_price);
    $stmt->bindParam(':list_date', $this->list_date);
    $stmt->bindParam(':sale_price', $this->sale_price);
    $stmt->bindParam(':sale_date', $this->sale_date);
    $stmt->bindParam(':id', $this->id);

    // execute the query
    if($stmt->execute()){
        return true;
    }

    return false;
  }

  // delete the product
  function delete(){

    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

    // prepare query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));

    // bind id of record to delete
    $stmt->bindParam(1, $this->id);

    // execute query
    if($stmt->execute()){
        return true;
    }

    return false;

  }

  // search products
  function search($keywords){

    // select all query
    $query = "SELECT
                *
            FROM
                " . $this->table_name . "
            WHERE
                street LIKE ?
            ORDER BY
                street DESC";

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // sanitize
    $keywords=htmlspecialchars(strip_tags($keywords));
    $keywords = "%{$keywords}%";

    // bind
    $stmt->bindParam(1, $keywords);

    // execute query
    $stmt->execute();

    return $stmt;
  }

  // read products with pagination
  public function readPaging($from_record_num, $records_per_page){

    // select query
    $query = "SELECT * FROM " . $this->table_name . " ORDER BY suburb DESC LIMIT ?, ?";

    // prepare query statement
    $stmt = $this->conn->prepare( $query );

    // bind variable values
    $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
    $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

    // execute query
    $stmt->execute();

    // return values from database
    return $stmt;
  }

  // used for paging products
  public function count(){
    $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";

    $stmt = $this->conn->prepare( $query );
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['total_rows'];
  }
}
?>
