<?php

/**
 * This file contains the RESTServiceControllerInterface interface
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

namespace eu\maxschuster\rest\controller;

use eu\maxschuster\rest\Service;

/**
 * Interface for all RESTService controllers, that could get registered at
 * RESTService instance.
 * @author Max Schuster <dev@maxschuster.eu>
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @package restservice
 */
interface ControllerInterface {
    
    /**
     * Sets the calling RESTService.
     * @param \eu\maxschuster\rest\Service $sevice 
     * Calling RESTService
     */
    public function setService(Service $sevice);
    
    /**
     * Checks if this Controller can handle the current request.
     * @return bool
     * Controller can handle the current request
     */
    public function checkResponsibility();
    
    /**
     * Handles the current request.
     */
    public function handle();
    
    /**
     * Checks if the given extension is supported.
     * @param string $extension
     * Extension
     * @return bool Extension is supported
     */
    public function extensionSupported($extension);
    
}

?>
