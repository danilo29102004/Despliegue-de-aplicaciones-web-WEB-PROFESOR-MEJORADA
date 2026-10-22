<?php

class Product{

    private $id;
    private $name;
    private $description;
    private $price;
    private $stock;
    private $img;

    public function __construct($id, $name, $description, $price, $stock, $img ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->stock = $stock;
        $this->img = $img;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getStock() {
        return $this->stock;
    }
    public function getImage() {
        return $this->img;
    }

}