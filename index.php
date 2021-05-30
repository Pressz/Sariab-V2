<?php
// Read configuration
include('config.php');

if (_Debug)
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
else
    // Turn off all error reporting
    error_reporting(0);

// Exception handler
include('Core/Exceptions.php');

// Models core
include('Core/Model.php');

// Libs
include('Libs/string.php');
include('Libs/apr1.php');
include('Libs/time.php');

// Controllers core
include('Core/Controller.php');
include('Core/PiController.php');

// Router
include('Core/App.php');

new App;
