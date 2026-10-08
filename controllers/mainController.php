<?php

//cargar modelos
require_once("models/User.php");
require_once("models/Product.php");
require_once("models/Order.php");
require_once("models/OrderLine.php");
require_once("models/ProductRepository.php");
require_once("models/OrderRepository.php");
require_once("models/OrderLineRepository.php");
require_once("models/UserRepository.php");

session_start();

if(isset($_GET['c'])){
    require_once("controllers/".$_GET['c']."Controller.php");
}

//acciones

//listar productos

//ver login
if(isset($_GET['login'])){
    require_once('views/login.phtml');
    exit;
}
//hacer login

if(isset($_GET['c'])){
    require_once("controllers/".$_GET['c']."Controller.php");
}
//logout

//register
if(isset($_GET['register'])){
    require_once('views/register.phtml');
    exit;
}
if(isset($_GET['checkout'])){
    $orderId = filter_input(INPUT_GET, 'checkout', FILTER_VALIDATE_INT);
    if($orderId === false || $orderId === null || $orderId <= 0 || !isset($_SESSION['user'])){
        http_response_code(400);
        exit('Identificador de pedido no válido.');
    }

    OrderRepository::checkoutOrder($orderId, $_SESSION['user']->getId());
    header('location: pedidoFinalizado.php');
    exit;
   }
//elimiar pedido


//añadir al carrito

//terminar pedido


// vista por defecto

$products=ProductRepository::getProducts();

require_once("views/mainView.phtml");

?>