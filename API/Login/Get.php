<?php
// Get login details (Controller)
namespace API\Login;
use API\Admin\Encryption\Get as GetEncCodes;

class Get extends Model {
    public $loginEmail;
    public $loginPwd;

    public function __construct($loginEmail, $loginPwd) {
        $this->loginEmail = $loginEmail;
        $this->loginPwd = $loginPwd;
    }

    public function returnData() {

        if ($this->valEmail()) {
            return "error email";
            exit;
        }

        if ($this->valPwd()) {
            return "error pwd";
            exit;
        }

        if ($this->privateQuery()) {
            $adminData = $this->privateQuery();
            $adminPwd = $this->decryptPwd($adminData['adminPwd']);
            if ($this->loginPwd === $adminPwd) {
                $logInUser = $this->privateQuery();
                unset($logInUser['adminPwd']);
                return $logInUser;
            } else {
                return "error not found";
                exit;
            }
        } else {
            return "error not found";
            exit;
        }
    }

    // decrypt password
    private function decryptPwd($adminPwd) {
        $getEncCodes = new GetEncCodes();
        $encCodes = $getEncCodes->returnData();
        if ($encCodes && isset($adminPwd)) {
            $encrypt_method = $encCodes['encMethod'];
            $secret_key = $encCodes['encKey'];
            $secret_iv = $encCodes['encIV'];

            // hash
            $key = hash('sha256', $secret_key);
            $iv = substr(hash('sha256', $secret_iv), 0, 16);
            $decryptedPwd = openssl_decrypt(base64_decode($adminPwd), $encrypt_method, $key, 0, $iv);
            return $decryptedPwd;

        } else {
            return "error encryption";
        }
    }

    // db query
    private function privateQuery() {
        return $this->query();
    }

    // validate login email
    private function valEmail() {
        if (!filter_var($this->loginEmail, FILTER_VALIDATE_EMAIL)) {
           return true;
        }
    }

    // validate login password
    private function valPwd() {
        $uppercase = preg_match('@[A-Z]@', $this->loginPwd);
        $lowercase = preg_match('@[a-z]@', $this->loginPwd);
        $number = preg_match('@[0-9]@', $this->loginPwd);
        $specialChars = preg_match('@[^\w]@', $this->loginPwd);
            
        if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($this->loginPwd) < 8) {
            return true;
        }
    }
}