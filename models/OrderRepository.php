<?php

class OrderRepository{

    public static function getOrdersByUserId($id){
        $db = DB::connect();
        $id = (int) $id;
        $query = 'SELECT * FROM orders WHERE buyer_id = ? AND status = 1';
        $statement = $db->prepare($query);
        $statement->bind_param('i', $id);
        $statement->execute();
        $result = $statement->get_result();

        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = new Order($row['id'], $row['buyer_id'], $row['total_price'], $row['date'], $row['status']);
        }

        return $orders;
    }

    public static function getCarritoByUserId($id){
        $db = DB::connect();
        $id = (int) $id;
        $query = 'SELECT * FROM orders WHERE status = 0 AND buyer_id = ? LIMIT 1';
        $statement = $db->prepare($query);
        $statement->bind_param('i', $id);
        $statement->execute();
        $result = $statement->get_result();

        if ($row = $result->fetch_assoc()) {
            return new Order($row['id'], $row['buyer_id'], $row['total_price'], $row['date'], $row['status']);
        }

        $query = 'INSERT INTO orders (buyer_id, total_price, date, status) VALUES (?, 0, NOW(), 0)';
        $statement = $db->prepare($query);
        $statement->bind_param('i', $id);
        $statement->execute();
        $orderId = $db->insert_id;

        if ($orderId === 0) {
            throw new RuntimeException('No se pudo crear el carrito del usuario.');
        }

        return new Order($orderId, $id, 0, date('Y-m-d H:i:s'), 0);
    }
    //finalizar pedido y el estado del order = 1
    public static function checkoutOrder($order_id, $buyer_id){
        $db = DB::connect();
        $query = 'UPDATE orders SET status = 1
            WHERE id = ? AND buyer_id = ? AND status = 0';
        $statement = $db->prepare($query);
        $statement->bind_param('ii', $order_id, $buyer_id);
        if(!$statement->execute()){
            $statement->close();
            return false;
        }

        $updated = $statement->affected_rows > 0;
        $statement->close();
        return $updated;
    }
    //eliminar pedido 
    public static function deleteOrder($order_id, $buyer_id){
        $db = DB::connect();
        $order_id = (int) $order_id;
        $buyer_id = (int) $buyer_id;

        $db->begin_transaction();

        $query = 'DELETE order_lines FROM order_lines
            INNER JOIN orders ON orders.id = order_lines.order_id
            WHERE order_lines.order_id = ? AND orders.buyer_id = ? AND orders.status = 1';
        $statement = $db->prepare($query);
        $statement->bind_param('ii', $order_id, $buyer_id);
        if(!$statement->execute()){
            $statement->close();
            $db->rollback();
            return false;
        }
        $statement->close();

        $query = 'DELETE FROM orders WHERE id = ? AND buyer_id = ? AND status = 1';
        $statement = $db->prepare($query);
        $statement->bind_param('ii', $order_id, $buyer_id);
        if(!$statement->execute()){
            $statement->close();
            $db->rollback();
            return false;
        }

        $deleted = $statement->affected_rows > 0;
        $statement->close();

        if(!$deleted){
            $db->rollback();
            return false;
        }

        $db->commit();
        return true;
    }
    
    
}