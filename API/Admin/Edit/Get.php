<?php
// Get admin (Controller)
namespace API\Admin\Edit;
use API\Admin\Encryption\Get as GetEncCodes;

class Get extends Model {
    public $adminID;

    public function __construct($adminID) {
        $this->adminID = $adminID;
    }

    public function returnData() {
        if ($this->privateQuery()) {
            $adminData = $this->privateQuery();
            return $this->decryptPwd($adminData);
        }
    }

    // decrypt password
    private function decryptPwd($adminData) {
        $getEncCodes = new GetEncCodes();
        $encCodes = $getEncCodes->returnData();
        if ($encCodes && isset($adminData['adminPwd'])) {
            $encrypt_method = $encCodes['encMethod'];
            $secret_key = $encCodes['encKey'];
            $secret_iv = $encCodes['encIV'];

            // hash
            $key = hash('sha256', $secret_key);
            $iv = substr(hash('sha256', $secret_iv), 0, 16);
            $decryptedPwd = openssl_decrypt(base64_decode($adminData['adminPwd']), $encrypt_method, $key, 0, $iv);
            $adminData['adminPwd'] = $decryptedPwd;

            return $adminData;
        }
    }

    private function privateQuery() {
        return $this->query();
    }
}