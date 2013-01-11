<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace eu\maxschuster\rest;

/**
 *
 * @author mschuster
 */
interface RESTAuthInterface {
    
    public function __construct(RESTService $service);
    
    public function checkAuth();
    
    public function getData();
    
    public function generateError();
    
}

?>
