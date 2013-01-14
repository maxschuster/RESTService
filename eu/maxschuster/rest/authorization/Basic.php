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

namespace eu\maxschuster\rest\authorization;

use eu\maxschuster\rest\authorization\AuthorizationInterface;
use eu\maxschuster\rest\Service;
use eu\maxschuster\rest\Request;

/**
 * This class can be used to perform a "Basic access authentication".
 *
 * @author Max Schuster <dev@maxschuster.eu>
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @link http://en.wikipedia.org/wiki/Basic_access_authentication
 * @package restservice
 */
abstract class Basic implements AuthorizationInterface {
    
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
     * @var Service
     */
    protected $service;
    
    /**
     * Request
     * @var Request
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
        if ($this->request->getMode() === Request::MODE_AJAX) {
            $this->service->setStatus(Service::STATUS_FORBIDDEN);
            $this->service->setContentType(Service::CONTENT_TYPE_TEXT);
            echo 'HTTP/1.0 403 Forbidden';
        } else {
            header('WWW-Authenticate: Basic realm="' . $this->realm . '"');
            $this->service->setStatus(Service::STATUS_UNAUTHORIZED);
            $this->service->setContentType(Service::CONTENT_TYPE_TEXT);
            echo 'HTTP/1.0 401 Unauthorized';
        }
        exit;
    }

    /**
     * Constructor of the auth class
     * @param Service $service Calling RESTService
     */
    public function __construct(Service $service) {
        $this->service = $service;
        $this->request = $service->getRequest();
    }
    
    /**
     * Can be used to access data that has eventually has been set through
     * RESTAuth::validateData()
     * @see Basic::validateData()
     * @return mixed Data of different types
     */
    public function getData() {
        return $this->data;
    }

    
}

?>
