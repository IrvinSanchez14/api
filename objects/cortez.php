<?php
class Cortez{
 
    // database connection and table name
    private $conn;
    private $table_name = "cortez";



 
    // object properties
    public $ID_cortez;
    public $total_efectivo;
    public $total_pos;
    public $total_compras;
    public $fechatiempo;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read products
    function read(){
 
        $query = "SELECT
               ID_cortez, total_efectivo, total_pos, total_compras, fechatiempo
            FROM
                " . $this->table_name . " p
            WHERE
                date(fechatiempo) = '" . date("Y-m-d") . "'
            ORDER BY
                ID_cortez DESC
            LIMIT
                1";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
 
        // execute query
        $stmt->execute();
 
        return $stmt;
    }

    // create product
    function create(){
 
        // query to insert record
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                total_efectivo=:total_efectivo, total_pos=:total_pos, total_compras=:total_compras, fechatiempo=:fechatiempo";
 
        // prepare query
        $stmt = $this->conn->prepare($query);
 
        // sanitize
        $this->total_efectivo=htmlspecialchars(strip_tags($this->total_efectivo));
        $this->total_pos=htmlspecialchars(strip_tags($this->total_pos));
        $this->total_compras=htmlspecialchars(strip_tags($this->total_compras));
        $this->fechatiempo=htmlspecialchars(strip_tags($this->fechatiempo));
 
        // bind values
        $stmt->bindParam(":total_efectivo", $this->total_efectivo);
        $stmt->bindParam(":total_pos", $this->total_pos);
        $stmt->bindParam(":total_compras", $this->total_compras);
        $stmt->bindParam(":fechatiempo", $this->fechatiempo);
 
        // execute query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    // used when filling up the update product form
function readOne(){
 
    // query to read single record
    $query = "SELECT
                c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    categories c
                        ON p.category_id = c.id
            WHERE
                p.id = ?
            LIMIT
                0,1";
 
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // bind id of product to be updated
    $stmt->bindParam(1, $this->id);
 
    // execute query
    $stmt->execute();
 
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // set values to object properties
    $this->name = $row['name'];
    $this->price = $row['price'];
    $this->description = $row['description'];
    $this->category_id = $row['category_id'];
    $this->category_name = $row['category_name'];
}

// update the product
function update(){
 
    // update query
    $query = "UPDATE
                " . $this->table_name . "
            SET
                total_efectivo=:total_efectivo,
                total_pos=:total_pos,
                total_compras=:total_compras
            WHERE
                ID_cortez=:ID_cortez";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->total_efectivo=htmlspecialchars(strip_tags($this->total_efectivo));
    $this->total_pos=htmlspecialchars(strip_tags($this->total_pos));
    $this->total_compras=htmlspecialchars(strip_tags($this->total_compras));
    $this->ID_cortez=htmlspecialchars(strip_tags($this->ID_cortez));
 
    // bind new values
    $stmt->bindParam(':total_efectivo', $this->total_efectivo);
    $stmt->bindParam(':total_pos', $this->total_pos);
    $stmt->bindParam(':total_compras', $this->total_compras);
    $stmt->bindParam(':ID_cortez', $this->ID_cortez);
 
    // execute the query
    if($stmt->execute()){
        return true;
    }else{
        return false;
    }
}


// delete the product
function delete(){
 
    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE ID_cortez = ?";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->ID_cortez=htmlspecialchars(strip_tags($this->ID_cortez));
 
    // bind id of record to delete
    $stmt->bindParam(1, $this->ID_cortez);
 
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
                c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    categories c
                        ON p.category_id = c.id
            WHERE
                p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?
            ORDER BY
                p.created DESC";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $keywords=htmlspecialchars(strip_tags($keywords));
    $keywords = "%{$keywords}%";
 
    // bind
    $stmt->bindParam(1, $keywords);
    $stmt->bindParam(2, $keywords);
    $stmt->bindParam(3, $keywords);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
}

// read products with pagination
public function readPaging($from_record_num, $records_per_page){
 
    // select query
    $query = "SELECT
                c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    categories c
                        ON p.category_id = c.id
            ORDER BY p.created DESC
            LIMIT ?, ?";
 
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