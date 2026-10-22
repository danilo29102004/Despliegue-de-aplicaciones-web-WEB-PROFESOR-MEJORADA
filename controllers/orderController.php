<?php

if(!isset($_SESSION['user'])){
    header('location: index.php?login');
    exit;
}

if(isset($_GET['add'])){

    //sacar el producto de la base de datos
    if(isset($_POST['id']) && isset($_POST['quantity'])){
    $product=ProductRepository::getProductById($_POST['id']);

    //tener el pedido en estado carrito del usuario
   $order=OrderRepository::getCarritoByUserId($_SESSION['user']->getId());

    // crear un orderline en pedido de usuario con producto
    if(OrderLineRepository::addOrderLineToOrder($order,$product,$_POST['quantity'])){
        //actualizar total del pedido
        
        $newTotal= $order->getTotal()+($product->getPrice()*$_POST['quantity']);
        $db=DB::connect();
        $q="UPDATE orders SET total_price=".$newTotal." WHERE id=".$order->getId();
        $db->query($q);

        header('location: index.php?c=order&show');
        exit;
    }

}
//devolviendo a la vista del carrito
   header('location: index.php');
   exit; 
   
}
// checkout del pedido
if(isset($_GET['checkout'])){
    $orderId = filter_input(INPUT_GET, 'checkout', FILTER_VALIDATE_INT);
    if($orderId === false || $orderId === null || $orderId <= 0){
        http_response_code(400);
        exit('Identificador de pedido no válido.');
    }

    OrderRepository::checkoutOrder($orderId, $_SESSION['user']->getId());
    header('location: views/pedidoFinalizado.php');
    exit;
}
//historial pedidos
if(isset($_GET['historis'])){
    $orders=OrderRepository::getOrdersByUserId($_SESSION['user']->getId());
    require_once('views/historialPedidos.php');
    exit;
}


if(isset($_GET['show'])){
       $order=OrderRepository::getCarritoByUserId($_SESSION['user']->getId());
    require_once('views/showOrderView.phtml');
    exit;
}
//eliminar línea del carrito
if(isset($_GET['deleteLine'])){
    $lineId = filter_input(INPUT_GET, 'deleteLine', FILTER_VALIDATE_INT);
    

    OrderLineRepository::deleteOrderLine($lineId, $_SESSION['user']->getId());
    header('location: index.php?c=order&show');
    exit;
}
//eliminar pedido desde el panel de administración
if(isset($_GET['delete']) && isset($_SESSION['user']) && $_SESSION['user']->getRol() == 'admin' && isset($_POST['id'])){
    $orderId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    header('location: index.php?c=product&panelAdmin');
    exit;
}

//eliminar pedido del historial del usuario
if(isset($_GET['delete'])){
    $orderId = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);

    OrderRepository::deleteOrder($orderId, $_SESSION['user']->getId());
    header('location: index.php?c=order&historis');
    exit;
}
