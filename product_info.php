<?php

if($_SERVER["REQUEST_METHOD"] == "GET")
{
    $product_id = $_GET['id'];
}

include ($_SERVER['DOCUMENT_ROOT']."/options/connection_database.php");


$name = '';
$price = '';
$description = '';
$sql = 'SELECT p.id, p.name, p.price, p.description 
        from tbl_products p
        where p.id=:id;';

$sth = $dbh->prepare($sql);
$sth->execute([':id' => $product_id]);
if ($row = $sth->fetch()) {
    $name = $row['name'];
    $price = $row['price'];
    $description = $row['description'];
}
$sql = "SELECT pi.name, pi.priority 
        FROM tbl_products_images pi
        WHERE pi.product_id=:id
        ORDER BY pi.priority;";
$sth = $dbh->prepare($sql);
$sth->execute([':id' => $product_id]);
$images = $sth->fetchAll();

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.min.css">
<!--    <link rel="stylesheet" href="css/style.css">-->
    <style>
        body{background-color: #000}.card{border:none}.product{background-color: #eee}.brand{font-size: 13px}.act-price{color:red;font-weight: 700}.dis-price{text-decoration: line-through}.about{font-size: 14px}.color{margin-bottom:10px}label.radio{cursor: pointer}label.radio input{position: absolute;top: 0;left: 0;visibility: hidden;pointer-events: none}label.radio span{padding: 2px 9px;border: 2px solid #ff0000;display: inline-block;color: #ff0000;border-radius: 3px;text-transform: uppercase}label.radio input:checked+span{border-color: #ff0000;background-color: #ff0000;color: #fff}.btn-danger{background-color: #ff0000 !important;border-color: #ff0000 !important}.btn-danger:hover{background-color: #da0606 !important;border-color: #da0606 !important}.btn-danger:focus{box-shadow: none}.cart i{margin-right: 10px}
    </style>
    <title>Інформація про продукт</title>
</head>
<body>
<?php
include ($_SERVER['DOCUMENT_ROOT']."/_header.php");
?>

<div class="container mt-5 mb-5">
    <div class="row d-flex justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="row">
                    <div class="col-md-6">
                        <div class="images p-3">
                            <div class="text-center p-4">
                                <img id="main-image" src="images/<?php echo $images[0]['name'] ?>"
                                     width="250"/>
                            </div>
                            <div class="thumbnail text-center">
                                <?php
                                foreach ($images as $img) {
                                    echo '<img onclick="change_image(this)"
                                            src="images/' . $img["name"] . '" width="70">';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="product p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center"><i class="fa fa-long-arrow-left"></i> <span
                                            class="ml-1">Back</span></div>
                                <i class="fa fa-shopping-cart text-muted"></i>
                            </div>
                            <div class="mt-4 mb-3">
                                <h5 class="text-uppercase"><?php echo $name; ?></h5>
                                <div class="price d-flex flex-row align-items-center">
                                    <span class="act-price">$<?php echo $price; ?></span>
                                </div>
                            </div>
                            <p class="about"><?php echo $description; ?></p>

                            <div class="cart mt-4 align-items-center">
                                <button class="btn btn-danger text-uppercase mr-2 px-4">Add to cart</button>
                                <i class="fa fa-heart text-muted"></i> <i class="fa fa-share-alt text-muted"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/bootstrap.bundle.min.js"></script>

<script>
    function change_image(image){
        var container = document.getElementById("main-image");
        container.src = image.src;
    }
    document.addEventListener("DOMContentLoaded", function(event) {
    });
</script>
</body>
</html>