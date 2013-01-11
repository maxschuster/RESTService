# RESTService
A small REST service for PHP 5.3+

# License
Apache License, Version 2.0
http://www.apache.org/licenses/LICENSE-2.0

# Requirements
* PHP 5.3
* Webserver with rewrite engine

# Usage

## Data folder
Create a folder that should be the base for your rest service. In this project
its the 'data' folder.
<br/><br/>
Create .htaccess file inside it with the following contents:
```htaccess
# Enable rewrite engine
RewriteEngine On

# Send everything inside this folder
# to the rest.php as GET parameter _REWRITE_
# and also keep other GET parameters
RewriteRule ^(.*)$ [[path_to_your_scrip]]?_REWRITE_=$1 [L,QSA,PT]
```
Ofcause you have to edit your [[path_to_your_scrip]]. It sould point to the php
file, that load the rest server.

## Create a RESTServer instance
First you have to create a server instance. And tell it the request URI that it
should handle:
```php
// Load the server
require_once 'rest.phar';

use eu\maxschuster\rest\RESTService;

$srv = new RESTService($_GET['_REWRITE_']);
```

## Create a controller and register them
Basicly all RESTService controller have to implement the interface
'RESTServiceControllerInterface'. There are also the pre configured abstract
classes 'RESTServiceController' and 'RESTServiceCRUDController' that you can use as
parent class for your Controller

```php

// Create some controllers:

SimpleController implements RESTServiceControllerInterface {
    // ...
}

MyController extends RESTServiceController {
    // ...
}

MyCRUDController extends RESTServiceCRUDController {
    // ...
}

// Register them:
$srv->addController(
    new SimpleController(),
    new MyController(),
    new MyCRUDController()
);
```

## Handle the Request
Now you just have to call handle to process the request:
```php
$srv->handle();
```

# Documentation
You can find a phpdoc inside the 'doc' folder or under
http://dev.maxschuster.eu/RESTService/doc/