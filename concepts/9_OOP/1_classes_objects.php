<?php
// What are Classes & Objects??
/*
 --> Classes are Blueprint / Templates for creating Objects
 --> Objects are instances of the classes.
*/

// Defining a class called Car
class Car {
    public $brand;  // Property
    public $color;  // Property

    // Method to display car details
    public function showDetails() {
        echo "Brand: " . $this->brand . ", Color: " . $this->color . "<br>\n";
    }
}

// Creating objects (instances) of the Car class
$car1 = new Car();
$car1->brand = "Toyota";
$car1->color = "Red";

$car2 = new Car();
$car2->brand = "Honda";
$car2->color = "Blue";

// Calling the method to show car details
$car1->showDetails();
$car2->showDetails();

?>