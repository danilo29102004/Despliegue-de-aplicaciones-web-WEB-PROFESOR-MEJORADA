<?php

class OrderLineRepository{
    public static function getOrderLinesByOrderId($order_id){
        $db=DB::connect();
        $query="SELECT * FROM order_lines WHERE order_id=$order_id";
        $result=$db->query($query);
        $orderLines=[];
        while($row=$result->fetch_assoc()){
            $orderLines[]=new OrderLine($row['id'], $row['product_id'], $row['quantity'], $row['price'], $row['order_id']);
        }
        return $orderLines;
    }

    public static function getOrderLineById($id){
        $db=DB::connect();
        $query="SELECT * FROM order_lines WHERE id=$id";
        $result=$db->query($query);
        $orderLine=$result->fetch_assoc();
        return new OrderLine($orderLine['id'], $orderLine['product_id'], $orderLine['quantity'], $orderLine['price'], $orderLine['order_id']);
    }

    public static function addOrderLineToOrder($order, $product, $quantity){
           $db=DB::connect();
        $q='INSERT into order_lines VALUES (null, '.$order->getId().', '.$product->getId().', '.$quantity.', '.$product->getPrice().')';
        $db->query($q);

        if($db->insert_id) return true;
        else return false;
    }

    //finalizar pedido, cambiar estado de la orden a 1
    public static function checkoutOrder($order_id){
        $db=DB::connect();
        $q='UPDATE orders SET status=1 WHERE id='.$order_id;
        $db->query($q);
        if($db->affected_rows>0) return true;
        else return false;
    }
    
    
}