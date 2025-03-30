<?php

/*
To understand Accesss Modifiers let's imagine an idle Bank Account where:
• The owner's name should be public (accessible to all).
• The account balance should be private (only the owner can see).

Things to keep in mind:
• public → Can be accessed anywhere.
• private → Can be accessed only inside the class.
• protected → Works like private, but also accessible in child classes (explained below).

*/

class BankAccount {
    public $owner;  // Public property (accessible everywhere)
    private $balance;  // Private property (only accessible inside class)

    public function __construct($owner, $balance) {
        $this->owner = $owner;
        $this->balance = $balance;
    }

    // Public method to display balance
    public function showBalance() {
        echo "Account Owner: " . $this->owner . ", <br>\nBalance: $" . $this->balance . " <br>\n";
    }

    // Private method: Cannot be accessed outside
    private function secretCode() {
        return "1234";
    }
}

$account = new BankAccount("Akash", 200000000);
$account->showBalance(); // ✅ Works

// echo $account->balance; // ❌ ERROR (Cannot access private property)
// echo $account->secretCode(); // ❌ ERROR (Cannot access private method)

?>
