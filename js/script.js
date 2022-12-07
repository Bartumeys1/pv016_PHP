$("#foto").change(function (e){
    var image = e.target.files[0];
    let url = URL.createObjectURL(image);
    console.log(image);
    if (!image.type.includes("image"))
        url ="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSJhtPqbjHLO40vkAgI34kxJw6Zztbnh88eag&usqp=CAU";

    document.getElementById("selectFoto").src=url;
});