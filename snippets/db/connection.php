<?php
try {
    $dbh = new PDO('mysql:host=localhost;dbname=cuestionario2', 'r3p0r73', '4f6caXzxdzAp,R{sdtXCuRib');
    $dbh->exec("SET CHARACTER SET utf8");
}
catch ( PDOException $e ){
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}