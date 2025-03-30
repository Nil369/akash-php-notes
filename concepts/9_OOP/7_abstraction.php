<?php
// Abastraction: Hides details and forces implementation in child classes
// Example: When you drive a car, you don’t need to know how the engine works internally. 

abstract class Animal {
    abstract public function makeSound(); // Must be implemented in child class
}

class Dog extends Animal {
    public function makeSound() {
        echo "Dog says: Woof! 🐶<br>";
    }
}

$dog = new Dog();
$dog->makeSound();

?>
