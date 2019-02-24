<?php
class Database{

  private $host = '127.0.0.1';
  private $db_name = 'auctions';
  private $username = 'root';
  private $password = ')Yx@2.afw8vRh@i&^6Oj';
  public $conn;

  //get the DB connection
  public function getConnection() {

    $this->conn = null;

    try{
      $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
      $this->conn->exec('set names utf8');
    }catch(PDOException $exception){
      echo 'Connection error: ' . $exception->getMessage();
    }

    return $this->conn;
  }
}
?>
