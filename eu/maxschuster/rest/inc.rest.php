<?php

/**
 * This file includes all files that are necessary for RESTService.
 * @author Max Schuster <dev@maxschuster.eu>
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @package restservice
 */

// Interfaces
require_once __DIR__ . '/controller/ControllerInterface.php';
require_once __DIR__ . '/controller/RequiresAuthorizationInterface.php';
require_once __DIR__ . '/authorization/AuthorizationInterface.php';

// Classes
require_once __DIR__ . '/Request.php';
require_once __DIR__ . '/Service.php';
require_once __DIR__ . '/controller/Controller.php';
require_once __DIR__ . '/controller/CRUD.php';
require_once __DIR__ . '/controller/BasicAuthorization.php';
require_once __DIR__ . '/authorization/Basic.php';
require_once __DIR__ . '/authorization/Digest.php';

?>
