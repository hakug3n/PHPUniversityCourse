<?php

try {
   $pdo = new PDO('mysql:host=localhost;port=3306;dbname=cms;charset=utf8', 'root', '');
}
catch (PDOException $e) {
    var_dump($e->getMessage());
    echo 'A problem occured with the database connection...';
    die();
}

return $pdo;