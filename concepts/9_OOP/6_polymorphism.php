<?php
// Polymorphism -> Single entity having multiple forms / Same method behaves differently in different classes
// Ex: A dog & a cat both "speak," but in different ways (bark vs meow).

class Animal {
    public function speak() {
        echo "The animal makes a sound.<br>\n";
    }
}

class Dog extends Animal {
    public function speak() {
        echo "The dog barks! 🐶<br>\n";
    }
}

class Cat extends Animal {
    public function speak() {
        echo "The cat meows! 🐱<br>\n";
    }
}

$dog = new Dog();
$dog->speak();

$cat = new Cat();
$cat->speak();

?>
