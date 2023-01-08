<?php
if($_SERVER["REQUEST_METHOD"] == "GET") {
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
    else
    {
        header('Location: /');
    }


    $sql = "SELECT pi.id, pi.name, pi.priority
FROM tbl_products_images pi
WHERE pi.product_id=:id
ORDER BY pi.priority;";
    $sth = $dbh->prepare($sql);
    $sth->execute([':id' => $product_id]);
    $images = $sth->fetchAll();

    //put base64 in input by file name
    for ($i=0 ; $i<count($images); $i++){
        $name_Image = $images[$i]['name'];
        $path = "images/$name_Image";
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $images[$i]["base64"] =$base64;
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST") {

    $dir_save = 'images/';
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    include($_SERVER['DOCUMENT_ROOT'] . "/options/connection_database.php");

     // update user
    $sql = 'UPDATE tbl_products SET name =:name, price=:price, description=:description  WHERE tbl_products.id=:id';
    $sth = $dbh->prepare($sql);
    $sth->bindParam(':id', $id);
    $sth->bindParam(':name', $name);
    $sth->bindParam(':price', $price);
    $sth->bindParam(':description', $description);
    $res = $sth->execute();

    //delete in folder ???????????????????????
     $sql_select_images = 'SELECT pi.name
     FROM tbl_products_images pi
     WHERE pi.product_id=:id';
    $stmt = $dbh->prepare($sql_select_images);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $delete_list = $stmt->fetchAll();

    foreach ($delete_list as $file){
        unlink($dir_save.$file["name"]);
    }

    //delete in database
    $sql_delete_images = 'DELETE FROM tbl_products_images WHERE product_id = :id';
    $stmt = $dbh->prepare($sql_delete_images);
    $stmt->bindParam(':id', $id);
    $stmt->execute();


    //save new images in database and folder
    include ($_SERVER['DOCUMENT_ROOT'] . "/lib/guidv4.php");
    $new_images = $_POST['images'];
    $count=1;
    foreach ($new_images as $base64) {

        $image_name = guidv4() . '.jpeg';
        $uploadfile = $dir_save . $image_name;
        list(, $data) = explode(',', $base64);
        $data = base64_decode($data);
        file_put_contents($uploadfile, $data);

        //save in database  table : tbl_products_images
        $sql = 'INSERT INTO tbl_products_images (name, datecreate, priority, product_id) VALUES(:name, NOW(), :priority, :product_id);';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':name', $image_name);
        $stmt->bindParam(':priority', $count);
        $stmt->bindParam(':product_id', $id);
        $stmt->execute();
        $count++;
    }

     header('Location: /edit.php?id='.$id);
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
    <link rel="stylesheet" href="css/font-awesome.min.css">
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
            <input type="hidden"  class="form-control" id="id" name="id" value="<?php echo  $product_id ?>">
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Ціна</label>
            <input type="text"  class="form-control"  id="price" name="price" value=<?php echo $price ?>>
        </div>
        <div class="mb-3">
            <div class="container">
                <div class="row" id="list_images">

                    <?php
                            foreach ($images as $img) {

                                echo '<div class="col-md-3 item-image">
                         <div class="row">
                            <div class="col-6">
                                <div class="fs-4 ms-2">
                                    <label for="'.$img['name'].'">
                                        <i class="fa fa-pencil" style="cursor: pointer;" aria-hidden="true"></i>
                                    </label>
                                    <input type="file" class="form-control d-none edit" id="'.$img['name'].'">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-end fs-4 text-danger me-2 remove">
                                    <i class="fa fa-times" style="cursor: pointer" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <img src="images/'.$img['name'].'" id="'.$img['name'].'_image" alt="photo" width="100%">
                            <input type="hidden" id="'.$img['name'].'_file" value="'.$img['base64'].'" name="images[]">
                        </div>
                        </div>';
                            }
                    ?>
                    <div class="col-md-3" id="selectImages">
                        <label for="image" style="cursor: pointer;" class="form-label text-success" >
                            <i class="fa fa-plus-square-o" style="font-size:120px" aria-hidden="true"></i>
                        </label>
                        <input type="file" class="form-control d-none" id="image" multiple>
                    </div>
                </div>

            </div>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Опис</label>
            <input type="text"  class="form-control"  id="description" name="description" value=<?php echo $description ?>>
        </div>
        <div style="margin-top: 20px ; text-align: center">
            <button class=" btn btn-primary btn-lg" type="submit" >Реданувати</button>
            <a href="/" class="btn btn-secondary btn-lg">Назад</a>
        </div>

    </form>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/jquery/jquery-3.6.1.min.js"></script>
<script src="js/drag_and_drop.js"></script>

<script>

    function uuidv4() {
        return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
            (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
        );
    }
    $(function (){
        //-----------------------SELECT IMAGES LIST---------------------------
        const image = document.getElementById("image");
        image.onchange = function (e) {
            const files = e.target.files;
            for (let i = 0; i < files.length; i++) {
                const reader = new FileReader();
                reader.addEventListener('load', function () {
                    const base64 = reader.result;
                    const id = uuidv4();
                    const data = `
                        <div class="row">
                            <div class="col-6">
                                <div class="fs-4 ms-2">
                                    <label for="${id}">
                                        <i class="fa fa-pencil" style="cursor: pointer;" aria-hidden="true"></i>
                                    </label>
                                    <input type="file" class="form-control d-none edit" id="${id}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-end fs-4 text-danger me-2 remove">
                                    <i class="fa fa-times" style="cursor: pointer" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <img src="${base64}" id="${id}_image" alt="photo" width="100%">
                            <input type="hidden" id="${id}_file" value="${base64}" name="images[]">
                        </div>
                    `;
                    const item = document.createElement('div');
                    item.className = "col-md-3 item-image";
                    item.innerHTML = data;
                    $("#selectImages").before(item);
                });
                const file = files[i];
                if (file)
                    reader.readAsDataURL(file);
            }
            image.value = "";
        }
        //-----------------------REMOVE ITEM BY LIST---------------------------------------------
        $("#list_images").on('click', '.remove', function () {
            $(this).closest('.item-image').remove();
        });

        //-----------------------CHANGE IMAGE LIST ITEM-------------------------------------
        let edit_id = 0;
        const reader = new FileReader();
        reader.addEventListener('load', () => {
            const base64 = reader.result;
            document.getElementById(`${edit_id}_image`).src = base64;
            document.getElementById(`${edit_id}_file`).value = base64;
        });


        $("#list_images").on('change', '.edit', function (e) {
            edit_id = e.target.id;
            const file = e.target.files[0];
            reader.readAsDataURL(file);
            this.value = "";
        });

    });

/*
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

 */
</script>

<!--
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
/*
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
*/
        ?>

    </div>
</div>
-->
</body>
</html>



