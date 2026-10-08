<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=Ñ, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    echo "inicio <a href=\"index.php\">volver</a><br>";

    foreach($orders as $order){
        echo "<br>pedido: ".$order->getId()." total: ".$order->getTotal()." fecha: ".$order->getDate() . "<a href=\"index.php?c=order&delete=" . $order->getId() . "\">eliminar pedido</a><br>";
    }
    ?>
</body>
</html>