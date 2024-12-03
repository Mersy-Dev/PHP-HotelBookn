<?php 
try{
    
//host name
define('DB_HOST','localhost');

//database name
define('DB_NAME','hotel-booking');

//database username
define('DB_USER','root');

//database password
define('DB_PASS','');


//connect to database
$conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER,DB_PASS);  
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

// if($conn == true){
//     echo "Connected";
// }else{
//     echo "Not Connected";
// }
}catch(PDOException $e){
    echo "Error: ".$e->getMessage();
}
