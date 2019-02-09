<?php
/**
 * Created by PhpStorm.
 * User: Patrik
 * Date: 09/02/2019
 * Time: 20:33
 */

use App\Tracker;

if(!isset($isAdmin)) {

    // Loaded when visited by non-admin user

    function __autoload($class) {

        $class = realpath('../') . '/' . str_replace('_', '/', $class) . '.php';

        require_once($class);
    }

    if(!isset($_POST['js'])) {
        $calledByJs = false;
    } else {
        $calledByJs = $_POST['js'];
    }

    new Tracker($calledByJs);

} else {

    // Loaded when visited from admin portal

    function __autoload($class) {

        $class = realpath('../../') . '/' . str_replace('_', '/', $class) . '.php';

        require_once($class);
    }

}
