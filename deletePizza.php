<?php
$deletePizzaId = $_POST['id'];


try {
$user = "root";
$pass="";
$dbh = new PDO('mysql:host=localhost;dbname=pv016', $user, $pass);

  $res = $dbh->prepare("DELETE FROM tbl_products WHERE id=?")->execute([$deletePizzaId]);
  echo "Delete: $res";
$dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

?>