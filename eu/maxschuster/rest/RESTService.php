<?php

/**
 * This file contains the RESTService class
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
 * This class represents the rest service. Controller can be registered in an
 * instance of this class and this class seeks the right controller for the
 * request to be processed.
 * @author Max Schuster <dev@maxschuster.eu>
 * @package restservice
 */
class RESTService {
    
    /*
     * Source for status code descriptions
     * Wikipedia, the free encyclopedia
     * URL: http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
     * DATE: 2012-01-10
     */
    
    // ===================
    // 2xx Success
    // ===================
    
    /**
     * 200 OK
     * Standard response for successful HTTP requests. The actual response will
     * depend on the request method used. In a GET request, the response will
     * contain an entity corresponding to the requested resource. In a POST
     * request the response will contain an entity describing or containing the
     * result of the action.
     * @const STATUS_OK
     */
    const STATUS_OK = 200;
    
    /**
     * 201 Created
     * The request has been fulfilled and resulted in a new resource being
     * created.
     * @const STATUS_CREATED
     */
    const STATUS_CREATED = 201;
    
    /**
     * 202 Accepted
     * The request has been accepted for processing, but the processing has not
     * been completed. The request might or might not eventually be acted upon,
     * as it might be disallowed when processing actually takes place.
     * @const STATUS_ACCEPTED
     */
    const STATUS_ACCEPTED = 202;
    
    /**
     * 204 No Content
     * The server successfully processed the request, but is not returning any
     * content.
     * @const STATUS_NO_CONTENT
     */
    const STATUS_NO_CONTENT = 204;
    
    // ===================
    // 4xx Client Error
    // ===================
    
    /**
     * 400 Bad Request
     * The request cannot be fulfilled due to bad syntax.
     * @const STATUS_BAD_REQUEST
     */
    const STATUS_BAD_REQUEST = 400;
    
    /**
     * 401 Unauthorized
     * Similar to 403 Forbidden, but specifically for use when authentication is
     * required and has failed or has not yet been provided. The response must
     * include a WWW-Authenticate header field containing a challenge applicable
     * to the requested resource. See Basic access authentication and Digest
     * access authentication.
     * @const STATUS_UNAUTHORIZED
     */
    const STATUS_UNAUTHORIZED = 401;
    
    /**
     * 403 Forbidden
     * The request was a valid request, but the server is refusing to respond to
     * it. Unlike a 401 Unauthorized response, authenticating will make no
     * difference. On servers where authentication is required, this commonly
     * means that the provided credentials were successfully authenticated but
     * that the credentials still do not grant the client permission to access
     * the resource (e.g. a recognized user attempting to access restricted
     * content).
     * @const STATUS_FORBIDDEN
     */
    const STATUS_FORBIDDEN = 403;
    
    /**
     * 404 Not Found
     * The requested resource could not be found but may be available again in
     * the future. Subsequent requests by the client are permissible.
     * @const STATUS_NOT_FOUND
     */
    const STATUS_NOT_FOUND = 404;
    
    /**
     * 405 Method Not Allowed
     * A request was made of a resource using a request method not supported by
     * that resource; for example, using GET on a form which requires data to be
     * presented via POST, or using PUT on a read-only resource.
     * @const STATUS_METHOD_NOT_ALLOWED
     */
    const STATUS_METHOD_NOT_ALLOWED = 405;
    
    /**
     * 409 Conflict
     * Indicates that the request could not be processed because of conflict in
     * the request, such as an edit conflict.
     * @const STATUS_CONFLICT
     */
    const STATUS_CONFLICT = 409;
    
    // ===================
    // 5xx Server Error
    // ===================
    /**
     * 500 Internal Server Error
     * A generic error message, given when no more specific message is suitable.
     * @const STATUS_INTERNAL_SERVER_ERROR
     */
    const STATUS_INTERNAL_SERVER_ERROR = 500;
    
    /** 
     * 501 Not Implemented
     * The server either does not recognize the request method, or it lacks the
     * ability to fulfill the request.
     * @const STATUS_NOT_IMPLEMENTED
     */
    const STATUS_NOT_IMPLEMENTED = 501;
    
    /**
     * Mimetype for json
     * @const CONTENT_TYPE_JSON
     */
    const CONTENT_TYPE_JSON = 'application/json';
    
     /**
     * Mimetype for plain text
     * @const CONTENT_TYPE_JSON
     */
    const CONTENT_TYPE_TEXT = 'text/plain';

    /**
     * Collection of controllers
     * @var RESTServiceControllerInterface[]
     */
    protected $controllers = array();
    
    /**
     * Parsed request
     * @var RESTRequest
     */
    protected $request;

    /**
     * Constructs the rest service
     * @param string $uri
     * Request URI from REWRITE or any other source
     */
    public function __construct($uri) {
        $this->request = new RESTRequest($uri,$_SERVER['REQUEST_METHOD']);
        //var_dump($this->request); die;
    }
    
    /**
     * Adds one or multible controllers to the service
     * @param RESTServiceControllerInterface $controller
     * Controller for the RESTService that implements
     * the RESTServiceControllerInterface interface.
     * @param RESTServiceControllerInterface $_ [optional]
     * Additional controllers...
     * @throws \UnexpectedValueException
     */
    public function addController($controller) {
        $n = func_num_args();
        for ($i = 0; $i < $n; $i++) {
            $c = func_get_arg($i);
            if ($c instanceof RESTServiceControllerInterface) {
                // Make shure that we have only one controller of that type
                $this->controllers[get_class($c)] = $c;
                continue;
            }
            throw new \UnexpectedValueException('All controllers must '.
                    'implement RESTServiceControllerInterface!');
        }
    }
    
    /**
     * Get parsed request
     * @return RESTRequest Parsed request
     */
    public function getRequest() {
        return $this->request;
    }
    
    /**
     * Sets the response HTTP status code. Be shure to call this function before
     * any content has been send to the client.
     * @param int $status HTTP status code; see STATUS_* constants!
     * @see RESTService::STATUS_OK
     * @see http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
     * @see http://php.net/manual/en/function.header.php
     */
    public function setStatus($status) {
        ob_clean(); // Try to clean buffer
        header(':', true, $status);
    }
    
    /**
     * Sets the response content type. Be shure to call this function before
     * any content has been send to the client.
     * @param string $mimetype
     * @see http://php.net/manual/en/function.header.php
     * @see http://en.wikipedia.org/wiki/Content-type
     */
    public function setContentType($mimetype) {
        header('Content-Type: ' . $mimetype, true);
    }
    
    /**
     * Start to handle the request.
     * @throws \Exception
     */
    public function handle() {
        try {
            foreach ($this->controllers as $controller) {
                $controller->setService($this);
                if (
                    $controller->checkResponsibility() && 
                    $controller->extensionSupported(
                        $this->request->getExtension()
                    )  
                ) {
                    $controller->handle();
                    return;
                }
            }
            // No handler found
            $this->setStatus(self::STATUS_NOT_IMPLEMENTED);
            $this->setContentType(self::CONTENT_TYPE_TEXT);
            echo "501 Not Implemented\n" .
            "The Server does not know how to handle your request!";
        } catch (\Exception $e) {
            $this->setStatus(self::STATUS_INTERNAL_SERVER_ERROR);
            throw $e;
        }
    }

    
}

?>
