<?php

require_once '../eu/maxschuster/rest/inc.rest.php';
//require_once 'rest.phar';
use eu\maxschuster\rest\Service;
use eu\maxschuster\rest\controller\CRUD;
use eu\maxschuster\rest\controller\Controller;

class TestUserCRUDController extends CRUD {
    
    protected function create() {
        $obj = new stdClass();
        $obj->action = 'user';
        $obj->value = $this->request->keywordValue('user');
        $obj->result = 'Create user "' . $obj->value . '"';
        $this->service->setStatus(Service::STATUS_CREATED);
        $this->service->setContentType(Service::CONTENT_TYPE_JSON);
        echo json_encode($obj);
    }

    protected function delete() {
        $obj = new stdClass();
        $obj->action = 'user';
        $obj->value = $this->request->keywordValue('user');
        $obj->result = 'delete user "' . $obj->value . '"';
        $this->service->setStatus(Service::STATUS_OK);
        $this->service->setContentType(Service::CONTENT_TYPE_JSON);
        echo json_encode($obj);
    }

    protected function read() {
        $obj = new stdClass();
        $obj->action = 'user';
        $obj->value = $this->request->keywordValue('user');
        $obj->result = 'read user "' . $obj->value . '"';
        $this->service->setStatus(Service::STATUS_OK);
        $this->service->setContentType(Service::CONTENT_TYPE_JSON);
        echo json_encode($obj);
    }

    protected function update() {
        $obj = new stdClass();
        $obj->action = 'user';
        $obj->result = 'update user "' . $obj->value . '"';
        $obj->result = array();
        $this->service->setStatus(Service::STATUS_OK);
        $this->service->setContentType(Service::CONTENT_TYPE_JSON);
        echo json_encode($obj);
    }

    public function checkResponsibility() {
        return $this->request->indexValue(0) == 'user';
    }

    public function extensionSupported($extension) {
        return strtolower($extension) === 'json';
    }

}

class TestBasicAuth extends \eu\maxschuster\rest\authorization\Basic {
    
    public function __construct(Service $service) {
        parent::__construct($service);
    }

    public function validateData() {
        return ($this->request->getPassword() === 'password' && $this->request->getUsername() === 'user');
    }

}

class SimpleSearchController extends eu\maxschuster\rest\controller\BasicAuthorization {
    
    protected $auth;
    
    public function setService(Service $sevice) {
        parent::setService($sevice);
        $this->auth = new TestBasicAuth($sevice);
    }
    
    public function authorizationFailed() {
        $this->auth->generateError();
    }

    public function checkResponsibility() {
        return $this->request->indexValue(0) == 'search';
    }

    public function extensionSupported($extension) {
        return strtolower($extension) === 'json';
    }

    public function handle() {
        $obj = new stdClass();
        $obj->action = 'search';
        $obj->value = $this->request->keywordValue('search');
        $obj->result = array();
        $this->service->setStatus(Service::STATUS_OK);
        $this->service->setContentType(Service::CONTENT_TYPE_JSON);
        echo json_encode($obj);
    }
    
    public function checkAuthorization() {
        $auth = new TestBasicAuth($this->service);
        return $auth->checkAuth();
    }
    
}

$srv = new Service($_GET['_REWRITE_']);
$srv->addController(new SimpleSearchController(), new TestUserCRUDController());
$srv->handle();


?>