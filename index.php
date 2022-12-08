<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">

    <title>Головна сторінка</title>
</head>
<body>
<?php include "_header.php";?>

<?php
try {
    $user = "root";
    $pass="";
    $dbh = new PDO('mysql:host=localhost;dbname=pv016', $user, $pass);
    ?>



<div class="container">
    <div class="row">
        <div class="col">
            <h1 class="text-center">Список продуктів</h1>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <section style="background-color: #eee;">
                <div class="container py-5">
                    <div class="row">
                        <?php
                        foreach($dbh->query('SELECT * from tbl_products') as $row) {
                            $image=$row["image"];
                            $price=$row["price"];
                            $name=$row["name"];
                            $id=$row["id"];
                            echo '
                            <div class="col-md-6 col-lg-4 mb-4 mb-md-0">
                            <div class="card">
                                <img src="images/pizza/'.$image.'"
                                     class="card-img-top" alt="Gaming Laptop"/>
                                <div class="card-body">

                                    <div class="d-flex justify-content-between mb-3">
                                        <h5 class="mb-0">'.$name.'</h5>
                                        <h5 class="text-dark mb-0">'.$price.' грн</h5>
                                    </div>

                                    <div class="mb-2 text-end">
                                        <button type="button" class="btn btn-success">Купить</button>
                                        <button type="button" class="btn btn-warning deleteProduct"  data-id='.$id.'>Удалити</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                            ';
                        }
                        ?>

                    </div>
                </div>
            </section>
        </div>
    </div>


</div>

    <?php
    $dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>

<script src="https://cdn.jsdelivr.net/npm/axios@1.1.2/dist/axios.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery/jquery-3.6.1.min.js"></script>
<script src = "js/script.js"></script>
</body>
</html>