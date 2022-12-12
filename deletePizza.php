<?php
$deletePizzaId = $_POST['id'];


include($_SERVER['DOCUMENT_ROOT'].'/options/connection_database.php');
  $res = $dbh->prepare("DELETE FROM tbl_products WHERE id=?")->execute([$deletePizzaId]);
  echo "Delete: $res";


?>