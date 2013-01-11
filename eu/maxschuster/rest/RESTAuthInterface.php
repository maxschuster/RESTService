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

/**
 * Interface for RESTService auth methods
 * 
 * @author Max Schuster <dev@maxschuster.eu>
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @package restservice
 */
interface RESTAuthInterface {
    
    /**
     * Contructor
     * @param RESTService $service The calling service
     */
    public function __construct(RESTService $service);
    
    /**
     * Checks the send login data
     * @return bool Login data is correct
     */
    public function checkAuth();
    
    /**
     * Can be used to access data that has eventually has been set through
     * RESTAuth::validateData()
     * @see RESTAuth::validateData()
     * @return mixed Data of different types
     */
    public function getData();
    
    /**
     * Generates an error, usually 'HTTP/1.0 401 Unauthorized'
     */
    public function generateError();
    
    /**
     * It should do the actualy check of the login data.
     * So run your database queries or what ever you use to check the login data
     * here. If you get complete user data in  this function you can store them
     * somewhere so it can access it later using RESTAuth::getData().
     * @see RESTAuthInterface::getData()
     */
    abstract public function validateData();
    
}

?>
