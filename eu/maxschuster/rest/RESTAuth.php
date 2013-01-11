<?php

namespace eu\maxschuster\rest;

use eu\maxschuster\rest\RESTAuthInterface;

/**
 * Description of RESTAuth
 *
 * @author mschuster
 */
abstract class RESTAuth implements RESTAuthInterface {
    
    /**
     * Login data
     * @var mixed
     */
    protected $data;

    /**
     * Realm
     * @var string
     */
    protected $realm = 'RESTService Auth';

    /**
     * REST service
     * @var RESTService
     */
    protected $service;
    
    /**
     * Request
     * @var RESTRequest
     */
    protected $request;

    public function checkAuth() {
        //var_dump($this->request->getUsername() === false, !$this->validateData()); die;
        if ($this->request->getUsername() === false || !$this->validateData()) {
            return false;
        }
        return true;
    }
    
    public function generateError() {
        header('WWW-Authenticate: Basic realm="' . $this->realm . '"');
        $this->service->setStatus(RESTService::STATUS_UNAUTHORIZED);
        $this->service->setContentType(RESTService::CONTENT_TYPE_TEXT);
        echo 'HTTP/1.0 401 Unauthorized';
        exit;
    }

    public function __construct(RESTService $service) {
        $this->service = $service;
        $this->request = $service->getRequest();
    }
    
    abstract public function validateData();
    
    public function getData() {
        return $this->data;
    }

    
}

?>
