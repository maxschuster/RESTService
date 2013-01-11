<?php

/**
 * This file includes all files that are necessary for RESTService.
 * @author Max Schuster <dev@maxschuster.eu>
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @package restservice
 */

// Interfaces
require_once 'RESTServiceControllerInterface.php';
require_once 'RESTAuthInterface.php';

// Classes
require_once 'RESTRequest.php';
require_once 'RESTService.php';
require_once 'RESTServiceController.php';
require_once 'RESTServiceCRUDController.php';
require_once 'RESTAuth.php';
require_once 'RESTAuthDigest.php';

?>
