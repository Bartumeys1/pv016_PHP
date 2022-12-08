$("#foto").change(function (e){
    var image = e.target.files[0];
    let url = URL.createObjectURL(image);
    console.log(image);
    if (!image.type.includes("image"))
        url ="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSJhtPqbjHLO40vkAgI34kxJw6Zztbnh88eag&usqp=CAU";

    document.getElementById("selectFoto").src=url;
});


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
        });
    });