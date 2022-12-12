<?php

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $many_images_name="";
    $dir_save = "images/";

    include ($_SERVER['DOCUMENT_ROOT'] . "/lib/guidv4.php");

    foreach ($_FILES["myimages"]["error"] as $key => $error) {
        if ($error == UPLOAD_ERR_OK) {
            $image_name = guidv4().'.jpeg';
            $many_images_name .=$image_name." ";

            //save image in folder
           $tmp_name = $_FILES["myimages"]["tmp_name"][$key];
           $uploadfile = $dir_save.$image_name;

            move_uploaded_file($tmp_name, $uploadfile);
        }
    }
    echo $many_images_name;

    include($_SERVER['DOCUMENT_ROOT'].'/options/connection_database.php');
    if (!empty($many_images_name))
    {
        $sql = "INSERT INTO `tbl_products` (`name`, `image`, `price`, `datecreate`, `description`) VALUES (:name, :image, :price, NOW(), :description);";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image', $many_images_name);
        $stmt->execute();
        header("Location: /");
        exit();
    }

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

    <title>Сторінка додавання продукту</title>
</head>
<body>
<?php include ($_SERVER['DOCUMENT_ROOT']."/_header.php");?>

<div class="container">
    <h1 class="text-center">Додати продукт</h1>
    <form method="post" enctype="multipart/form-data" class="col-md-6 offset-md-3">
        <div class="mb-3">
            <label for="name" class="form-label">Назва</label>
            <input type="text"  class="form-control"  id="name" name="name">
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Ціна</label>
            <input type="text"  class="form-control"  id="price" name="price">
        </div>
        <div class="mb-3">
            <label for="myimages" class="form-label">Фото</label>
            <input type="file"  class="form-control"  id="myimages" name="myimages[]" multiple="multiple">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Опис</label>
            <input type="text"  class="form-control"  id="description" name="description">
        </div>
        <button class=" btn btn-primary btn-lg" type="submit" style="margin-top: 20px">Додати товар</button>
    </form>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>