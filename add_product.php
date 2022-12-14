<?php

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $dir_save = "images/";


    include ($_SERVER['DOCUMENT_ROOT'] . "/lib/guidv4.php");
    include($_SERVER['DOCUMENT_ROOT'].'/options/connection_database.php');


    //save Product in database table tbl_products adn get last added product id

        $sql = "INSERT INTO `tbl_products` (`name`, `price`, `datecreate`, `description`) VALUES (:name, :price, NOW(), :description);";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        $last_id = $dbh->lastInsertId();


//get and save images files
foreach ($_FILES["myimages"]["error"] as $key => $error) {
    if ($error == UPLOAD_ERR_OK) {
        $image_name = guidv4() . '.jpeg';
        $product_prioriy = $key+1;

        //save in database  table : tbl_products_images
        $sql = "INSERT INTO `tbl_products_images` (`name`, `datecreate`, `priority`, `product_id`) VALUES (:name, NOW(), :priority, :product_id);";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':name', $image_name);
        $stmt->bindParam(':priority', $product_prioriy);
        $stmt->bindParam(':product_id', $last_id);
        if($stmt->execute())
        {
            //save image in folder
            $tmp_name = $_FILES["myimages"]["tmp_name"][$key];
            $uploadfile = $dir_save.$image_name;
            move_uploaded_file($tmp_name, $uploadfile);
        }
    }
}
    header("Location: /");
exit();

}
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">

    <title>???????????????? ?????????????????? ????????????????</title>
</head>
<body>
<?php include ($_SERVER['DOCUMENT_ROOT']."/_header.php");?>

<div class="container">
    <h1 class="text-center">???????????? ??????????????</h1>
    <form method="post" enctype="multipart/form-data" class="col-md-6 offset-md-3">
        <div class="mb-3">
            <label for="name" class="form-label">??????????</label>
            <input type="text"  class="form-control"  id="name" name="name">
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">????????</label>
            <input type="text"  class="form-control"  id="price" name="price">
        </div>
        <div class="mb-3">
            <label for="myimages" class="form-label">????????</label>
            <input type="file"  class="form-control"  id="myimages" name="myimages[]" multiple="multiple">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">????????</label>
            <input type="text"  class="form-control"  id="description" name="description">
        </div>
        <button class=" btn btn-primary btn-lg" type="submit" style="margin-top: 20px">???????????? ??????????</button>
    </form>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>