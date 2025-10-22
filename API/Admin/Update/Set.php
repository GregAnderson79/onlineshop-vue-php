<?php
// Update admin (Controller)
namespace API\Admin\Update;
use API\Admin\Encryption\Get as GetEncCodes;

class Set extends Model {
    public $adminName;
    public $adminEmail;
    public $adminPwd;
    public $adminID;
        
    public function __construct($adminName, $adminEmail, $adminPwd, $adminID) {
        $this->adminName = $adminName;
        $this->adminEmail = $adminEmail;
        $this->adminPwd = $adminPwd;
        $this->adminID = $adminID;
    }

    public function process() {

        if ($this->valName()) {
            return "error name";
            exit;
        }

        if ($this->valEmail()) {
            return "error email";
            exit;
        }

        if ($this->valPwd()) {
            return "error pwd";
            exit;
        }

        if (!$this->validateID()) {
            return "error ID";
            exit;
        }

        // encryption
        $getEncCodes = new GetEncCodes();
        $encCodes = $getEncCodes->returnData();
        if ($encCodes) {
            $encrypt_method = $encCodes['encMethod'];
            $secret_key = $encCodes['encKey'];
            $secret_iv = $encCodes['encIV'];

            // hash
            $key = hash('sha256', $secret_key);
            $iv = substr(hash('sha256', $secret_iv), 0, 16);

            $encrptedPwd = openssl_encrypt($this->adminPwd, $encrypt_method, $key, 0, $iv);
            $encrptedPwd = base64_encode($encrptedPwd);
            $this->adminPwd = $encrptedPwd;
        } else {
            return "error";
            exit;
        }

        if ($this->privateQuery() === true) { 
            return "updated";
        } else {
            return "error";
        }
    }

    // model
    private function privateQuery() {
        return $this->query();
    }

    // validate admin name
    private function valName() {
        if (strlen($this->adminName) < 1) {
            return true;
        }
    }

    // validate admin email
    private function valEmail() {
        if (!filter_var($this->adminEmail, FILTER_VALIDATE_EMAIL)) {
           return true;
        }
    }

    // validate admin password
    private function valPwd() {
        $uppercase = preg_match('@[A-Z]@', $this->adminPwd);
        $lowercase = preg_match('@[a-z]@', $this->adminPwd);
        $number = preg_match('@[0-9]@', $this->adminPwd);
        $specialChars = preg_match('@[^\w]@', $this->adminPwd);
            
        if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($this->adminPwd) < 8) {
            return true;
        }
    }

    // validate admin ID
    private function validateID() {
        return ctype_digit($this->adminID);
    }
}