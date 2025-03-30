<?php
// Encapsulation restricts direct access to class properties for security.
// Encapsulation means Encapsulating -> [ Variables(data) + Methods (functions) ] inside a class

class BankAccount {
    private $balance;

    public function __construct($initialBalance) {
        $this->balance = $initialBalance;
    }

    // Public method to safely get the balance
    public function getBalance() {
        return $this->balance;
    }

    // Public method to safely deposit money
    public function deposit($amount) {
        if ($amount > 0) {
            $this->balance += $amount;
            echo "Deposited $amount. New Balance: $" . $this->balance . "<br>\n";
        } else {
            echo "Invalid amount!<br>\n";
        }
    }
}

$account = new BankAccount(500);
echo "Initial Balance: $" . $account->getBalance() . "<br>\n";
$account->deposit(200);

// In this Example: Encapsulation ensures the balance can't be modified directly!
?>
