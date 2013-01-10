<?php

//require_once '../eu/maxschuster/rest/inc.rest.php';
require_once 'rest.phar';
use eu\maxschuster\rest\RESTService;
use eu\maxschuster\rest\RESTServiceCRUDController;
use eu\maxschuster\rest\RESTServiceController;

class TestUserCRUDController extends RESTServiceCRUDController {
    
    protected function create() {
        $obj = new stdClass();
        $obj->action = 'user';
        $obj->value = $this->request->keywordValue('user');
        $obj->result = 'Create user "' . $obj->value . '"';
        $this->service->setStatus(RESTService::STATUS_CREATED);
        $this->service->setContentType(RESTService::CONTENT_TYPE_JSON);
        echo json_encode($obj);
    }

    protected function delete() {
        $obj = new stdClass();
        $obj->action = 'user';
        $obj->value = $this->request->keywordValue('user');
        $obj->result = 'delete user "' . $obj->value . '"';
        $this->service->setStatus(RESTService::STATUS_OK);
        $this->service->setContentType(RESTService::CONTENT_TYPE_JSON);
        echo json_encode($obj);
    }

    protected function read() {
        $obj = new stdClass();
        $obj->action = 'user';
        $obj->value = $this->request->keywordValue('user');
        $obj->result = 'read user "' . $obj->value . '"';
        $this->service->setStatus(RESTService::STATUS_OK);
        $this->service->setContentType(RESTService::CONTENT_TYPE_JSON);
        echo json_encode($obj);
    }

    protected function update() {
        $obj = new stdClass();
        $obj->action = 'user';
        $obj->result = 'update user "' . $obj->value . '"';
        $obj->result = array();
        $this->service->setStatus(RESTService::STATUS_OK);
        $this->service->setContentType(RESTService::CONTENT_TYPE_JSON);
        echo json_encode($obj);
    }

    public function checkResponsibility() {
        return $this->request->indexValue(0) == 'user';
    }

    public function extensionSupported($extension) {
        return strtolower($extension) === 'json';
    }

}

class SimpleSearchController extends RESTServiceController {
    
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
        $this->service->setStatus(RESTService::STATUS_OK);
        $this->service->setContentType(RESTService::CONTENT_TYPE_JSON);
        echo json_encode($obj);
    }
    
}

$srv = new RESTService($_GET['_REWRITE_']);
$srv->addController(new SimpleSearchController(), new TestUserCRUDController());
$srv->handle();


?>