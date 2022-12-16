<?php
//if($_SERVER["REQUEST_METHOD"] == "GET") {
    $product_id = $_GET['id'];
if ($product_id <0)
    exit();

    $id = '';
    $name = '';
    $price = '';
    $description = '';


    include($_SERVER['DOCUMENT_ROOT'] . "/options/connection_database.php");

    $sql = 'SELECT p.id, p.name, p.price, p.description
from tbl_products p
where p.id=:id;';

    $sth = $dbh->prepare($sql);
    $sth->execute([':id' => $product_id]);
    if ($row = $sth->fetch()) {
        $id = $row["id"];
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
//}

if($_SERVER["REQUEST_METHOD"] == "POST")
{


    $sql = 'UPDATE tbl_products SET name =:name, price=:price, description=:description  WHERE tbl_products.id=:id';

    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $sth = $dbh->prepare($sql);
    $sth->bindParam(':id', $product_id);
    $sth->bindParam(':name', $name);
    $sth->bindParam(':price', $price);
    $sth->bindParam(':description', $description);
    $res = $sth->execute();
   // header("Location: /");
    //exit();
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
    <title>Редагування продукту</title>
</head>
<body>
<?php include ($_SERVER['DOCUMENT_ROOT']."/_header.php");?>
<div class="container">
    <h1 class="text-center">Редагувати продукт</h1>
    <form method="post" enctype="multipart/form-data" class="col-md-6 offset-md-3">
        <div class="mb-3">
            <label for="name" class="form-label">Назва</label>
            <input type="text"  class="form-control"  id="name" name="name" value=<?php echo $name ?>>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Ціна</label>
            <input type="text"  class="form-control"  id="price" name="price" value=<?php echo $price ?>>
        </div>
        <div class="mb-3">
            <div class="thumbnail text-center d-flex justify-content-center" id="images_view">
                <label for="foto"  id="0" onchange="change_handler(this)"  class="box" style="position: relative;" draggable="false">
                    <div id="queryElement">
                        <img src="images/default_portret.png"
                             width="150"   height="150"
                             alt="default foto"
                             id ="defaultFoto"/>
                        <input type="file"  id="foto" name="foto"   style="display: none"    >
                    </div>
                    <input type="button" id="RemoveImage" class="btn-close"  hidden="hidden" style="position: absolute;left: 0px; top: 0px">
                </label>
                <?php
                foreach ($images as $img) {
                    echo '<label for="foto"  id="0" onchange="change_handler(this)"  class="box" style="position: relative;" draggable="false">
                    <div id="queryElement">
                        <img src="images/'.$img['name'].'"
                             width="150"   height="150"
                             alt="default foto"
                             id ="defaultFoto"/>
                        <input type="file"  id="foto" name="foto"   style="display: none"    >
                    </div>
                    <input type="button" id="RemoveImage" class="btn-close"  style="position: absolute;left: 0px; top: 0px">
                </label>';
                }
                ?>

            </div>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Опис</label>
            <input type="text"  class="form-control"  id="description" name="description" value=<?php echo $description ?>>
        </div>
        <button class=" btn btn-primary btn-lg" type="submit" style="margin-top: 20px">Реданувати</button>
    </form>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/drag_and_drop.js"></script>
<script>
    function change_handler(element)
    {
       let clone = element.cloneNode(true);
        clone.setAttribute('id',"insercElement");

        clone.onchange= null;
        clone.removeAttribute("onchange");
        clone.removeEventListener('onchange',change_handler); //???

        clone.querySelector(`#RemoveImage`).addEventListener("click" , function (){
            clone.remove();
        });
        clone.setAttribute("draggable" , true);

        var input_loadFile = clone.querySelector('#foto');
        var files = input_loadFile.files;
        var fr = new FileReader();
        fr.onload = function () {
            clone.querySelector("#defaultFoto").src = fr.result;
        }
        fr.readAsDataURL(files[0]);
        clone.addEventListener('dragenter', dragEnter)
        clone.addEventListener('dragover', dragOver);
        clone.addEventListener('dragleave', dragLeave);
        clone.addEventListener('drop', drop);
        document.getElementById("images_view").appendChild(clone);
    }

    function single_change_handler(element)
    {
        var input_loadFile = element.querySelector('#foto');
        var files = input_loadFile.files;
        var fr = new FileReader();
        fr.onload = function () {
            element.querySelector("#defaultFoto").src = fr.result;
        }
        fr.readAsDataURL(files[0]);
    }
</script>
</body>
</html>