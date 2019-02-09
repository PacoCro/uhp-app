<?php
/**
 * Created by PhpStorm.
 * User: Patrik
 * Date: 09/02/2019
 * Time: 20:34
 */

$isAdmin = true;

include realpath('../../') . '/App/Middleware/preload.php';

// This could go into separate class that handles admin panel if there was more time
$connection = new \App\DB();
$db = $connection->getDB();

$stmt = $db->query("SELECT * from user_data");
$users = $stmt->fetchAll();

?>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>UHP App - Admin panel</title>
    <meta name="description" content="UHP App - Homepage">
    <meta name="author" content="Patrik K">

    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

<h1>UHP App - Admin Panel</h1>
<div style="margin-left: auto; margin-right:auto; width: 1200px;">

    <table style="width: 100%">
        <tr>
            <th>ID</th>
            <th>IP</th>
            <th>REFERRER</th>
            <th>USER AGENT</th>
            <th>REMOTE HOST</th>
            <th>TIME ON SITE</th>
            <th>TIME OF FIRST REQUEST</th>
            <th>TIME OF LAST REQUEST</th>
        </tr>
        <?php
            foreach ($users as $user) {
        ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= $user['ip'] ?></td>
                <td><?= $user['http_referer'] ?></td>
                <td><?= $user['http_user_agent'] ?></td>
                <td><?= $user['remote_host'] ?></td>
                <td><?= $user['time_on_site'] ?> seconds</td>
                <td><?= $user['itime'] ?></td>
                <td><?= $user['utime'] ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
</div>

</body>
</html>