<?php
/** from http://www.php.net/manual/en/pdo.connections.php /zeus **/
//'SELECT * from weapons'
function get_connection($user, $pass, $prepd_sql) {
    $qres = array();
    try {
        $dbh = new PDO('mysql:host=localhost;dbname=duelsystem', $user, $pass);
//        $dbh->exec("SET CHARACTER SET utf8");
        $sth = $dbh->prepare($prepd_sql);
        $sth->execute();
        /* Fetch all of the remaining rows in the result set */
        $qres = $sth->fetchAll(PDO::FETCH_ASSOC);
        /*echo 'Qres: ';
		print_r($qres);
		echo '<br>';*/
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    return $qres;
}
