
    var deleteProduct = document.querySelectorAll('.deleteProduct');
    deleteProduct.forEach( element => {
        element.addEventListener('click', function (event){
            var id = event.target.getAttribute('data-id');
            let res = window.confirm("Точно видалити піцу?");
            if (!res)return;

            let formData = new FormData();
            formData.append('id',id);

            axios.post('/deletePizza.php',formData).then(res => {
                console.log(res)});

            //clear DOM (element of card)
            element.parentElement.parentElement.parentElement.parentElement.remove();
            //delete product image
            //...
        });
    });


    //cropper
$(document).ready(function(){

    var $modal = $('#modal');

    var image = document.getElementById('sample_image');

    var cropper;

    $('#foto').change(function(event){
        var files = event.target.files;
        if (!files[0].type.includes("image"))
            return;
        var done = function(url){
            image.src = url;
            $modal.modal('show');
        };

        if(files && files.length > 0)
        {
            reader = new FileReader();

            reader.onload = function(event)
            {
                done(reader.result);
            };
            reader.readAsDataURL(files[0]);
        }
    });

    $modal.on('shown.bs.modal', function() {
        cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 3,
            preview:'.preview'
        });
    }).on('hidden.bs.modal', function(){
        cropper.destroy();
        cropper = null;
    });

    $('#crop').click(function(){
        canvas = cropper.getCroppedCanvas({
            width:400,
            height:400
        });

        canvas.toBlob(function(blob){
            url = URL.createObjectURL(blob);
            var reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function(){
                var base64data = reader.result;
                $.ajax({
                    url:'upload.php',
                    method:'POST',
                    data:{image:base64data},

                }).then(res => {
                    $modal.modal('hide');
                    document.getElementById("selectFoto").src=res;
                });
            };
        });
    });

    var cansleCropBtns = document.querySelectorAll("#canselCrop");
    cansleCropBtns.forEach(btn => {
        btn.addEventListener('click',function (){
            $modal.modal('hide');
        });
    });
});