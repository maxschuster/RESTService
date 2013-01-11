<?php

/**
 * This file contains the RESTServiceCRUDController class
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
 * Primitive RESTService CRUD controller. Can be used as Template for
 * controllers that create, read, update and delete resources
 * @author Max Schuster <dev@maxschuster.eu>
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @package restservice
 * @link http://en.wikipedia.org/wiki/Create,_read,_update_and_delete
 */
abstract class RESTServiceCRUDController extends RESTServiceController {
    /**
     * Handles the current request.
     */
    public function handle() {
        switch ($this->request->getType()) {
            case RESTRequest::TYPE_POST:
                $this->create();
                break;
            case RESTRequest::TYPE_GET:
                $this->read();
                break;
            case RESTRequest::TYPE_PUT:
                $this->update();
                break;
            case RESTRequest::TYPE_DELETE:
                $this->delete();
                break;
        }
    }
    
    /**
     * Creates a new resource.
     */
    abstract protected function create();
    
    /**
     * Reads an existing resource.
     */
    abstract protected function read();
    
    /**
     * Updates an existing resource.
     */
    abstract protected function update();
    
    /**
     * Deletes an existing resource.
     */
    abstract protected function delete();
    
}

?>
