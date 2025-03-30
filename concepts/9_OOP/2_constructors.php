<?php
class Car {
    public $brand;
    public $color;

    // Constructor to set values automatically
    public function __construct($brand, $color){
        $this->brand = $brand;
        $this->color = $color;
    }

    public function showDetails() {
        echo "Brand: " . $this->brand . ", Color: " . $this->color . "<br>\n";
    }
}

// Creating objects with constructor
$car1 = new Car("Toyota", "Red");
$car2 = new Car("Honda", "Blue");

$car1->showDetails();
$car2->showDetails();

?>
