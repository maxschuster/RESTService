<?php

/**
 * This file contains the RESTRequest class
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
 * Contains the request and offers ways to access its data.
 * @author Max Schuster <dev@maxschuster.eu>
 * @package restservice
 */
class RESTRequest {
    
    /**
     * Unknown Request type
     * @const TYPE_UNKNOWN
     */
    const TYPE_UNKNOWN = 1;
    
    /**
     * Request type GET
     * @const TYPE_GET
     */
    const TYPE_GET = 2;
    
    /**
     * Request type POST
     * @const TYPE_POST
     */
    const TYPE_POST = 4;
    
    /**
     * Request type PUT
     * @const TYPE_PUT
     */
    const TYPE_PUT = 8;
    
    /**
     * Request type DELETE
     * @const TYPE_DELETE
     */
    const TYPE_DELETE = 16;
    
    /**
     * All request types
     * @const TYPE_ALL
     */
    const TYPE_ALL = 2147483647;
    
    /**
     * Request type; see TYPE_* constants
     * @var int
     */
    protected $type;
    
    /**
     * Requests file extension
     * @var string
     */
    protected $extension;
    
    /**
     * Requests filename
     * @var string
     */
    protected $filename;

    /**
     * URI path segments parts
     * @var array
     */
    protected $path;
    
    /**
     * URI path segments parts + filename
     * @var array
     */
    protected $pathWithFilename;
    
    /**
     * Number of elements inside $this->pathWithFilename
     * @var int
     */
    protected $pathWithFilenameCount;

    /**
     * The given URI
     * @var string
     */
    protected $uri;

    /**
     * Constructs a new request
     * @param int|string $type
     * Request type; see TYPE_* constants
     * @param string $uri
     * Request URI
     */
    public function __construct($uri, $type = NULL) {
        if (!$type) {
            $type = $_SERVER['REQUEST_METHOD'];
        }
        $this->setType($type);
        $this->setUri($uri);
    }
    
    /**
     * 
     * @param string $keyword
     * Keyword to find
     * @param int|array $offset
     * Offset/s to get
     * @return array|string|null
     * Keywords value. Array if $offset is an array
     */
    public function keywordValue($keyword, $offset = 1) {
        $arrayMode = is_array($offset);
        if ($arrayMode) {
            $array = array();
        }
        for ($i = 0; $i < $this->pathWithFilenameCount; $i++) {
            if (isset($this->pathWithFilename[$i]) && $this->pathWithFilename[$i] === $keyword) {
                if ($arrayMode) {
                    foreach ($offset as $o) {
                        $array[] = isset($this->pathWithFilename[$i+$o]) ?
                            $this->pathWithFilename[$i+$o] : null;
                    }
                } else {
                    return
                        isset($this->pathWithFilename[$i+$offset]) ?
                            $this->pathWithFilename[$i+$offset] : null;
                }
            }
        }
        if ($arrayMode) {
            return $array;
        }
        return NULL;
    }
    
    /**
     * Gets the value of the given index.
     * @param int $index Index to search
     * @return string|null Value for index
     */
    public function indexValue($index) {
        return isset($this->pathWithFilename[$index]) ?
                $this->pathWithFilename[$index] : null;
    }

    /**
     * Gets the request type; see TYPE_* constants
     * @return int
     * Request type
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Gets the request type; see TYPE_* constants
     * @param int|string $type
     * Request type
     */
    protected function setType($type) {
        if (is_int($type)) {
            $this->type = (int)$type;
            return;
        } elseif (is_string($type)) {
            $type = strtoupper($type);
            switch ($type) {
                case 'GET':
                    $this->type = self::TYPE_GET;
                    break;
                case 'POST':
                    $this->type = self::TYPE_POST;
                    break;
                case 'PUT':
                    $this->type = self::TYPE_PUT;
                    break;
                case 'DELETE':
                    $this->type = self::TYPE_DELETE;
                    break;
                default:
                    $this->type = self::TYPE_UNKNOWN;
                    break;
            }
            return;
        }
        throw new \UnexpectedValueException('Invalid value for $type ' . gettype($type) . '(' . $type . ')');
    }
    
    /**
     * Gets the requests file extension
     * @return string Requests file extension
     */
    public function getExtension() {
        return $this->extension;
    }

    /**
     * Sets the requests file extension
     * @param string $extension Requests file extension
     */
    protected function setExtension($extension) {
        $this->extension = $extension;
    }

    /**
     * Gets the seperated path segments
     * @return array Seperated path segments
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * Sets the seperated path segments
     * @param array $path Seperated path segments
     */
    protected function setPath($path) {
        $this->path = $path;
    }

    /**
     * Gets the requests URI
     * @return string Requests URI
     */
    public function getUri() {
        return $this->uri;
    }

    /**
     * Sets the requests URI
     * @param string $uri Requests URI
     */
    protected function setUri($uri) {
        $pathInfo = pathinfo($uri);
        $path = array();
        $filename = '';
        if (isset($pathInfo['dirname'])) {
            foreach (explode('/', $pathInfo['dirname']) as $dirname) {
                if (!empty($dirname) && $dirname !== '.') {
                    $path[] = $dirname;
                }
            }
        }
        if (isset($pathInfo['filename'])) {
            $filename = $pathInfo['filename'];
        }
        $extension = isset($pathInfo['extension']) ? $pathInfo['extension'] : null;
        $this->setExtension($extension);
        $this->setPath($path);
        if (!empty($filename)) {
            $path[] = $filename;
        }
        $this->setPathWithFilename($path);
        $this->setFilename($filename);
        $this->uri = $uri;
    }
    
    /**
     * Gets the requests filename
     * @return string Request filename
     */
    public function getFilename() {
        return $this->filename;
    }

    /**
     * Sets the requests filename
     * @param string $filename Request filename
     */
    protected function setFilename($filename) {
        $this->filename = $filename;
    }

    /**
     * Get path segments + filename
     * @return array Path segments + filename
     */
    public function getPathWithFilename() {
        return $this->pathWithFilename;
    }

    /**
     * Set path segments + filename
     * @param array $pathWithFilename Path segments + filename
     */
    public function setPathWithFilename($pathWithFilename) {
        $this->pathWithFilename = $pathWithFilename;
        $this->pathWithFilenameCount = sizeof($pathWithFilename);
    }
    
}

?>
