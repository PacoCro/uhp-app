<?php
/**
 * Created by PhpStorm.
 * User: Patrik
 * Date: 09/02/2019
 * Time: 21:03
 */

include realpath('../') . '/App/Middleware/preload.php';

$response = array('User activity logged.');

echo json_encode($response);