<?php

class ProductRepository{

    public static function getProducts(){
        $db=DB::connect();
        $query="SELECT * FROM products";
        $result=$db->query($query);
        $products=[];
        while($product=$result->fetch_assoc()){
            $products[]=new Product($product['id'], $product['name'], $product['description'], $product['price'], $product['stock'], $product['img']);
        }
        return $products;
    }

    public static function getProductById($id){
        $db=DB::connect();
        $query="SELECT * FROM products WHERE id=$id";
        $result=$db->query($query);
        $product=$result->fetch_assoc();
        return new Product($product['id'], $product['name'], $product['description'], $product['price'], $product['stock'], $product['img']);
    }

    public static function deleteProductById($id){
        $db = DB::connect();
        $query = 'DELETE FROM products WHERE id = ?';
        $statement = $db->prepare($query);
        $statement->bind_param('i', $id);

        if(!$statement->execute()){
            $statement->close();
            return false;
        }

        $deleted = $statement->affected_rows > 0;
        $statement->close();
        return $deleted;
    }
    public static function addProduct($name, $description, $price, $stock,){
        $db = DB::connect();
        $query = 'INSERT INTO products (name, description, price, stock, img) VALUES (?, ?, ?, ?, ?)';
        $statement = $db->prepare($query);
        $statement->bind_param('ssdi', $name, $description, $price, $stock);

        if(!$statement->execute()){
            $statement->close();
            return false;
        }

        $inserted = $statement->affected_rows > 0;
        $statement->close();
        return $inserted;
    }
    public static function updateProduct($id, $name, $description, $price, $stock, $image){
        $db = DB::connect();
        $query = 'UPDATE products SET name = ?, description = ?, price = ?, stock = ? WHERE id = ?';
        $statement = $db->prepare($query);
        $statement->bind_param('ssdii', $name, $description, $price, $stock, $id);

        if(!$statement->execute()){
            $statement->close();
            return false;
        }

        $updated = $statement->affected_rows > 0;
        $statement->close();
        return $updated;
    }


    
    
}