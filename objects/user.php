<?php
class User{
  
    // database connection and table name
    private $conn;
    private $table_name = "v_user_login";
  
    // object properties
    public $id;
    public $username;
    public $password;
    public $last_updated;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read products
    function read(){
        // select all query
        $query = "SELECT id, username, password, last_updated FROM " . $this->table_name . " ";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    function create(){
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                username=:username, password=:password, last_updated=:last_updated";
      
        // prepare query
        $stmt = $this->conn->prepare($query);
      
        // sanitize
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->password=htmlspecialchars(strip_tags($this->password));
      
        // bind values
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":last_updated", $this->last_updated);
      
        // execute query
        if($stmt->execute()){
            return true;
        }
      
        return false;
          
    }

    // used when filling up the update product form
    function readOne(){
  
    // query to read single record
    $query = "SELECT id, username, password, last_updated FROM " . $this->table_name . "
            WHERE username = ?
            LIMIT 0,1";
  
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
  
    // bind id of product to be updated
    $stmt->bindParam(1, $this->username);
  
    // execute query
    $stmt->execute();
  
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // set values to object properties
    $this->password = $row['password'];
    $this->last_updated = $row['last_updated'];
    }

    function update(){
  
        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    password = :password,
                    last_updated = :last_updated
                WHERE
                    username = :username";
      
        // prepare query statement
        $stmt = $this->conn->prepare($query);
      
        // sanitize
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->last_updated = date('Y-m-d H:i:s');
        // bind new values
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':last_updated',$this->last_updated);
      
        // execute the query
        if($stmt->execute()){
            return true;
        }
      
        return false;
    }

    function delete(){
  
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE username = ?";
      
        // prepare query
        $stmt = $this->conn->prepare($query);
      
        // sanitize
        $this->username=htmlspecialchars(strip_tags($this->username));
      
        // bind id of record to delete
        $stmt->bindParam(1, $this->username);
      
        // execute query
        if($stmt->execute()){
            return true;
        }
      
        return false;
    }

}
