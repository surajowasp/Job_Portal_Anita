<?php

/* 
Ways to connect to a MySQL Database
1. MySQLi extension
2. PDO
*/
// Connecting to the Database
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname="workwise"

// // Create a connection
// $conn = mysqli_connect($servername, $username, $password,$dbname);

// // Die if connection was not successful
// if (!$conn){
//     die("Sorry we failed to connect: ". mysqli_connect_error());
// }
// else{
//     echo "Connection was successful";
// }

$conn =mysqli_connect('localhost','root','','workwise');

if($conn){
    // echo "conn success!";
}

?>
