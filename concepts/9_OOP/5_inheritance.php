<?php

// Example: A Car is a type of Vehicle. Instead of writing everything again, 
// we can inherit properties from a parent class.

// Inheritance basically allows us to reuse our code without re-writing everything!

class Vehicle {
    public $brand;
    public function __construct($brand) {
        $this->brand = $brand;
    }

    public function start() {
        echo $this->brand . " is starting... <br>\n";
    }
}

// Car inherits properties & methods from Vehicle
class Car extends Vehicle {
    public $model;
    public function __construct($brand, $model) {
        parent::__construct($brand);
        $this->model = $model;
    }

    public function showDetails() {
        echo "Car: " . $this->brand . " - " . $this->model . " <br>\n";
    }
}

$myCar = new Car("Toyota", "Camry");
$myCar->start();
$myCar->showDetails();

?>