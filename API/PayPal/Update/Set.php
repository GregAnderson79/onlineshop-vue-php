<?php
// Update PayPal (Controller)
namespace API\PayPal\Update;

class Set extends Model {
    public $payPalEmail;
        
    public function __construct($payPalEmail) {
        $this->payPalEmail = $payPalEmail;
    }

    public function process() {

        if ($this->valEmail()) {
            return "error email";
            exit;
        }

        if ($this->privateQuery()) { 
            return "updated";
        } else {
            return "error";
        }
    }

    // model
    private function privateQuery() {
        return $this->query();
    }

    // validate admin email
    private function valEmail() {
        if (!filter_var($this->payPalEmail, FILTER_VALIDATE_EMAIL)) {
           return true;
        }
    }
}