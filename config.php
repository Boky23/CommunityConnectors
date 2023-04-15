<?php
//Enter database login details and create connection

//DB LOGIN
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'cc_web');

//Connect to DB
$c = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($c === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>