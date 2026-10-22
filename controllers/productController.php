<?php

require_once __DIR__ . '/../helpers/fileHelper.php';

if(isset($_GET['new'])){
    require_once("views/newProduct.phtml");
    exit;
}

if(isset($_GET['add']) && isset($_SESSION['user']) && $_SESSION['user']->getRol() == 'admin'){
    if(isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price']) && isset($_POST['stock'])){
        if(fileHelper::uploadFile(
            $_FILES['image']['tmp_name'],
            $_FILES['image']['name']
        )){
            $fileName = $_FILES['image']['name'];
           
        }
        else {
            $fileName = "";
        }


        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/'.$_FILES['image']['name']);
        $q= "INSERT INTO products VALUES (NULL, '". $_POST['name'] ."', '". $_POST['description'] ."', ". $_POST['price'] .", ". $_POST['stock'] ." , '". $_FILES['image']['name'] ."')";
        $db=DB::connect();
        $db->query($q);
        if($db->insert_id){
            header("Location: index.php");
            exit;
        }
    }

header("Location: index.php?c=product&new");
exit;
}
if(isset($_GET['panelAdmin']) && isset($_SESSION['user']) && $_SESSION['user']->getRol() == 'admin'){
    $products = ProductRepository::getProducts();
    $orders = OrderRepository::getAllOrders();
    require_once("./views/panelAdmin.phtml");
    exit;
}
//borar producto
if(isset($_GET['delete']) && isset($_SESSION['user']) && $_SESSION['user']->getRol() == 'admin'){
    $productId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if($productId === false || $productId === null || $productId <= 0){
        http_response_code(400);
        exit('Identificador de producto no válido.');
    }

    if(!ProductRepository::deleteProductById($productId)){
        http_response_code(400);
        exit('No se pudo eliminar el producto.');
    }

    header('location: index.php?c=product&panelAdmin');
    exit;
}
if(isset($_GET['productsAdmin']) && isset($_SESSION['user']) && $_SESSION['user']->getRol() == 'admin'){
    $products = ProductRepository::getProducts();
    require_once("./views/productsAdmin.phtml");
    exit;
}
if(isset($_GET['update']) && isset($_SESSION['user']) && $_SESSION['user']->getRol() == 'admin'){
    $productId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
    $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);

    header('location: index.php?c=product&productsAdmin');
    exit;
}
if(isset($_GET['edit']) && isset($_SESSION['user']) && $_SESSION['user']->getRol() == 'admin'){
    $productId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if($productId === false || $productId === null || $productId <= 0){
        http_response_code(400);
        exit('Identificador de producto no válido.');
    }

    $product = ProductRepository::getProductById($productId);
    require_once("./views/editarProductosView.phtml");
    exit;
}
