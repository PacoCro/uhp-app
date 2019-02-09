<?php
/**
 * Created by PhpStorm.
 * User: Patrik
 * Date: 09/02/2019
 * Time: 19:51
 */

namespace App;

use App\DB;

/*
 * This class handles all user tracking/logging
 */
class Tracker
{

    private $trackingData = array();
    private $user;
    private $calldeByJs;


    /*
     * Tracking flow control.
     */
    public function __construct($calledByJs = false)
    {
        // Find out if tracker is called by page refresh, or JavaScript call.
        $this->calldeByJs = $calledByJs;

        // Collect data about the visitor.
        $this->extractTrackingData();

        // Check if this is request is coming from existing user or new user
        $userExists = $this->userExists();

        if (!$userExists) {
            $this->insertTrackingdata();
        } else {
            $this->updateTrackingData();
        }
    }

    /*
     * Gather all data about the user.
     */
    private function extractTrackingData() {

        $this->trackingData = array(
            'http_user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'http_referer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'itime' => date('Y-m-d H-i-s', time()),
            'remote_host' => isset($_SERVER['REMOTE_HOST']) ? $_SERVER['REMOTE_HOST'] : null,
            'time_on_site' => 0,
            'pages_visited' => 1
        );

    }


    /*
     * Check if visitor that is sending requests is existing or new
     */
    private function userExists() {

        $trackingData = $this->trackingData;

        $connection = new DB();
        $db = $connection->getDB();

        $stmt = $db->prepare("SELECT * FROM user_data where ip = :ip order by itime desc LIMIT 1");

        $stmt->bindParam(':ip', $trackingData['ip']);
        $stmt->execute();

        $users = $stmt->fetchAll();

        if (count($users) > 0) {

            $this->user = $users[0];

            // If same visitor came to the site 24h after initial request, consider him as new visitor
            if(strtotime($this->user['itime']) < (time() - 86400)) {

                return false;
            }

            $return = true;
        } else {
            $return = false;
        }

        return $return;

    }


    /**
     * For new users, insert tracking data into the database.
     */
    private function insertTrackingdata() {

        $trackingData = $this->trackingData;

        $connection = new DB();
        $db = $connection->getDB();

        $stmt = $db->prepare("INSERT INTO user_data (http_user_agent, http_referer, ip, itime, remote_host, time_on_site, utime, pages_visited) 
                                        VALUES (:http_user_agent, :http_referer, :ip, :itime, :remote_host, :time_on_site, :utime, :pages_visited)");
        $stmt->bindParam(':http_user_agent', $trackingData['http_user_agent']);
        $stmt->bindParam(':http_referer', $trackingData['http_referer']);
        $stmt->bindParam(':ip', $trackingData['ip']);
        $stmt->bindParam(':itime', $trackingData['itime']);
        $stmt->bindParam(':remote_host', $trackingData['remote_host']);
        $stmt->bindParam(':time_on_site', $trackingData['time_on_site']);
        $stmt->bindParam(':utime', $trackingData['itime']);
        $stmt->bindParam(':pages_visited', $trackingData['pages_visited']);

        $stmt->execute();

    }


    /*
     * Edit user data for already existing user requests.
     */
    public function updateTrackingData() {

        $trackingData = $this->trackingData;
        $ctime = date('Y-m-d H-i-s', time());


        // Calculate new time on site
        $trackingData['time_on_site'] = $this->user['time_on_site'] + (time() - strtotime($this->user['utime']));

        // Calculate pages visited
        if($this->calldeByJs == false) {
            // Only increment visits on page refresh (not on JavaScript tracker call)
            $trackingData['pages_visited'] = $this->user['pages_visited'] + 1;
        } else {
            $trackingData['pages_visited'] = $this->user['pages_visited'];
        }

        $connection = new DB();
        $db = $connection->getDB();

        $stmt = $db->prepare("UPDATE user_data SET time_on_site = :time_on_site, utime = :utime, pages_visited = :pages_visited
                                        WHERE id = :id");

        $stmt->bindParam(':id', $this->user['id']);
        $stmt->bindParam(':time_on_site', $trackingData['time_on_site']);
        $stmt->bindParam(':utime', $ctime);
        $stmt->bindParam(':pages_visited', $trackingData['pages_visited']);

        $stmt->execute();

    }
}