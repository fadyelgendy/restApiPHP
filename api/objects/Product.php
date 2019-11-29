<?php

class Product
{
    //database connection and table name
    private $conn;
    private $table_name = "products";

    // objects properties
    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $category_name;
    public $created;

    //constructor with $db and database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    //read Products
    public function read(){
        // Select all query
        $query = "SELECT 
            c.name as category_name, p.id, p.name, p.description
                , p.price, p.category_id, p.created 
            FROM ".
                $this->table_name ." p  
                    LEFT JOIN  categories c 
                    ON 
                        p.category_id = c.id 
                    ORDER BY 
                        P.created DESC ";
        // prepare query statement

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        return $stmt;
    }

    /**
     * Create Method
     */

    public function create(){
        //query to insert record
        $query = "INSERT INTO ".
            $this->table_name .
            " SET name=:name, price=:price, description=:description,
             category_id=:category_id, created=:created";

        //Prepare Query
        $stmt = $this->conn->prepare($query);

        //Sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->created = htmlspecialchars(strip_tags($this->created));

        //Bind values
        $stmt->binParam(":name", $this->name);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":created", $this->created);

        //execute Query
        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    /**
     * Used when filling up update form
     */
    public function readOne(){
        // Query to read Single Record
        $query = "SELECT c.name as category_name,
                p.id, p.name, p.description, p.price, p.category_id
                p.created FROM " .
            $this->table_name ." p
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE p.id = ? LIMIT 0,1";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        //bind id of the product to be updated
        $stmt->bindParam(1, $this->id);

        //execute query
        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        //set values to object properties
        $this->name = $row["name"];
        $this->price = $row["price"];
        $this->description = $row["description"];
        $this->category_id = $row["category_id"];
        $this->category_name = $row["category_name"];
    }

    // Update product
    public function update(){
        //Update query
        $query = "UPDATE ". $this->table_name.
            " SET 
                name = :name,
                price = :price,
                description = :description,
                category_id = :category_id
                
            WHERE
                 id = :id";

        //prepare statement.
        $stmt = $this->conn->prepare($query);

        //Sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        //Bind Params
        $stmt->bindParams(":name", $this->name);
        $stmt->bindParams(":price", $this->price);
        $stmt->bindParams(":description", $this->description);
        $stmt->bindParams(":category_id", $this->category_id);
        $stmt->bindParams(":id", $this->id);

        //Execute the query
        if($stmt->execute()){
            return true;
        } else {
            return fales;
        }
    }

    // Delete function
    public function delete(){
        // delete query
        $query = "DELETE FROM ". $this->table_name." WHERE id = ?";

        //prepare query
        $stmt = $this->conn->prepare($query);

        //Sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));

        //Bind Param values
        $stmt->bindParam("1", $this->id);

        //execute query
        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Search Function
    public function search($keywords) {
        // select all query
        $query = "SELECT 
            c.name as category_name,
            p.id,
            p.name,
            p.price,
            p.description,
            p.category_id,
            p.created
        FROM
            ".$this->table_name ." 
        p LEFT JOIN 
            categories c
        ON p.category_id = c.id
        WHERE 
            p.name LIKE ? OR p.description LIKE ? c.name LIKE ?
        ORDER BY 
            p.created DESC";

        // prepare Statement
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        //bind
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
    }

    public function readPaging($form_record_num, $record_per_page){
        // select query
        $query = "SELECT 
            c.name as category_name,
            p.id,
            p.name,
            p.description,
            p.price,
            p.category_id,
            p.created 
        FORM 
            " . $this->table_name. " p
        LEFT JOIN
            categories c 
        ON 
            p.category_id = c.id 
        ORDER BY 
            p.created DESC LIMIT ?, ?";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind variable values
        $stmt->bindParam(1, $form_record_num, PDO::PARAM_INT);
        $stmt->bindParam(1, $record_per_page, PDO::PARAM_INT);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    //count function used for paging products
    public function count() {
        $query = "SELECT COUNT(*) as total_rows FROM ".
            $this->table_name." ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }
}


