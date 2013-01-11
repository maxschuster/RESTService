<?php

namespace eu\maxschuster\rest;

use eu\maxschuster\rest\RESTService;

/**
 * Description of RESTAuthDigest
 *
 * @author mschuster
 */
abstract class RESTAuthDigest extends RESTAuth {
    
    protected $password;

    public function checkAuth() {
        if ($this->request->getDigest() === false) {
            return false;
        }
        
        if (
            !($data = $this->httpDigestParse($this->request->getDigest())) ||
            $this->validateData()
        ) {
            return FALSE;
        }
        
        return true;
    }
    
    public function generateError() {
        $this->service->setStatus(RESTService::STATUS_UNAUTHORIZED);
        header('WWW-Authenticate: Digest realm="'.$this->realm.
           '",qop="auth",nonce="'.uniqid().'",opaque="'.md5($this->realm).'"');
        $this->service->setContentType(RESTService::CONTENT_TYPE_TEXT);
        echo 'HTTP/1.0 401 Unauthorized';
        exit;
    }
    
    /**
     * 
     * @param type $txt
     * @return type
     * @see http://php.net/manual/en/features.http-auth.php#example-349
     */
    protected function httpDigestParse($txt) {
        $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
        $data = array();
        $keys = implode('|', array_keys($needed_parts));

        preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

        foreach ($matches as $m) {
            $data[$m[1]] = $m[3] ? $m[3] : $m[4];
            unset($needed_parts[$m[1]]);
        }

        return $needed_parts ? false : $data;
    }
    
    protected function compareResponse() {
        $A1 = md5($data['username'] . ':' . $this->realm . ':' . $this->getPassword($data['username']));
        $A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
        $valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);
        return ($data['response'] != $valid_response);
    }
    
    protected function getPassword() {
        return $this->password;
    }
    
}

?>
