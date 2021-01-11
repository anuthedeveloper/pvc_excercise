<?php
//  ABSPATH, directory or absolute path
define( 'ABSPATH', __DIR__ . '/');

define('INC', 'includes');

error_reporting(0);

// Include files required for initialization.
require(ABSPATH . INC. '/functions.php');

require_db();
