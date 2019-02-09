<?php
/**
 * Created by PhpStorm.
 * User: Patrik
 * Date: 09/02/2019
 * Time: 20:33
 */

use App\Tracker;

/**
 * Router/middleware part of the logic which routs the request
 * whether it is coming from admin panel or from visitor on frontpage
 */
if(!isset($isAdmin)) {
    // Loaded when visited by non-admin user

    function __autoload($class) {

        $class = realpath('../') . '/' . str_replace('_', '/', $class) . '.php';

        require_once($class);
    }

    // Check if request came via JS script
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
