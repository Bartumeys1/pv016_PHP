<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/style.css">

    <title>Сторінка реєстрації</title>
</head>
<body>
<?php include ($_SERVER['DOCUMENT_ROOT']."/_header.php");?>
<div class="container">
    <div class="row"><div class="col">
            <h1 class="text-center">Регістрація</h1>
        </div>

        <div class="row">
            <div class="col">
                <div style="display: flex;justify-content: center; ">
                    <div class="col-md-7 col-lg-8">
                        <form class="needs-validation" novalidate="">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label for="firstName" class="form-label">Приівище</label>
                                    <input type="text" class="form-control" id="firstName" placeholder="" value="" required="">
                                    <div class="invalid-feedback">
                                        Valid first name is required.
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <label for="lastName" class="form-label">Ім'я</label>
                                    <input type="text" class="form-control" id="lastName" placeholder="" value="" required="">
                                    <div class="invalid-feedback">
                                        Valid last name is required.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="country" class="form-label">Країна</label>
                                    <select class="form-select" id="country" required="">
                                        <option hidden>Оберіть країну</option>
                                        <option>Україна</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a valid country.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Телефон</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="" value="" required="">
                                    <div class="invalid-feedback">
                                        Valid last name is required.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="email" class="form-label">Електронна пошта <span class="text-muted">(Optional)</span></label>
                                    <input type="email" class="form-control" id="email" placeholder="you@example.com">
                                    <div class="invalid-feedback">
                                        Please enter a valid email address for shipping updates.
                                    </div>
                                </div>

                                <div class=" col-12 mb-3 mt-3">
                                    <label for="foto">Фото
                                        <img src="images/default_portret.png"
                                             width="150"   height="150"
                                             alt="default foto"
                                             id ="selectFoto"/>
                                    </label>
                                    <input type="file"  id="foto" name="foto" style="display: none"    >

                                </div>
                            </div>

                            <div class="col-sm-12">
                                <label for="password" class="form-label">Пароль</label>
                                <input type="password" class="form-control" id="password" >

                            </div>

                            <button class=" btn btn-primary btn-lg" type="submit" style="margin-top: 20px">Continue to checkout</button>
                        </form>

                        <br/>
                       <?php include ($_SERVER['DOCUMENT_ROOT']."/_cropper.php");?>
                    </div>
                </div>
            </div>
        </div>
</div>

<script src="js/jquery/jquery-3.6.1.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/cropperjs"></script>
<script src="js/script.js"></script>
</body>
</html>