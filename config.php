<?php
/* database configuration file to be used to form link with database */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'hekima');

/*conecting to database */
$link =  mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($link === false)
{
    die("ERROR: Could not establish connection" . mysqli_connect_error());
}
?>