<?php

/**
 * This file contains the RESTAuth class
 * @author Max Schuster <dev@maxschuster.eu>
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @package restservice
 */

/*
 * Copyright 2012 Max Schuster 
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace eu\maxschuster\rest;

use eu\maxschuster\rest\RESTAuthInterface;

/**
 * This class can be used to perform a "Basic access authentication".
 *
 * @author Max Schuster <dev@maxschuster.eu>
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @link http://en.wikipedia.org/wiki/Basic_access_authentication
 * @package restservice
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

    /**
     * Checks the send login data
     * @return bool Login data is correct
     */
    public function checkAuth() {
        //var_dump($this->request->getUsername() === false, !$this->validateData()); die;
        if ($this->request->getUsername() === false || !$this->validateData()) {
            return false;
        }
        return true;
    }
    
    /**
     * Generates a 'HTTP/1.0 401 Unauthorized' error and will STOP the script.
     */
    public function generateError() {
        header('WWW-Authenticate: Basic realm="' . $this->realm . '"');
        $this->service->setStatus(RESTService::STATUS_UNAUTHORIZED);
        $this->service->setContentType(RESTService::CONTENT_TYPE_TEXT);
        echo 'HTTP/1.0 401 Unauthorized';
        exit;
    }

    /**
     * Constructor of the auth class
     * @param RESTService $service Calling RESTService
     */
    public function __construct(RESTService $service) {
        $this->service = $service;
        $this->request = $service->getRequest();
    }
    
    /**
     * This function must get overridden.
     * It should do the actualy check of the login data.
     * So run your database queries or what ever you use to check the login data
     * here. If you get complete user data in  this function you can store them
     * inside the RESTAuth::$data property so it can access it later using
     * RESTAuth::getData()
     * @see RESTAuth::$data
     * @see RESTAuth::getData()
     */
    abstract public function validateData();
    
    /**
     * Can be used to access data that has eventually has been set through
     * RESTAuth::validateData()
     * @see RESTAuth::validateData()
     * @return mixed Data of different types
     */
    public function getData() {
        return $this->data;
    }

    
}

?>
