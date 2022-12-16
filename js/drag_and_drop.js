/* draggable element */
//const item = document.querySelector('.item');

//item.addEventListener('dragstart', dragStart);


$select_drop_element_clone ='';
$target_drop_element_clone='';
$delete_select_element ='';



/* drop targets */
/*
const boxes = document.querySelectorAll('.box');

boxes.forEach(box => {
    box.addEventListener('dragenter', dragEnter)
    box.addEventListener('dragover', dragOver);
    box.addEventListener('dragleave', dragLeave);
    box.addEventListener('drop', drop);
});

 */


function dragEnter(e) {
    e.preventDefault();
    console.log("dragEnter");
    if($select_drop_element_clone == '')
    {
        $delete_select_element=e.target.parentElement;
        $select_drop_element_clone = e.target.parentElement.cloneNode(true);
    }

    e.target.classList.add('drag-over');
}

function dragOver(e) {
    e.preventDefault();
    console.log("dragOver");
    e.target.classList.add('drag-over');
}

function dragLeave(e) {
    console.log("dragLeave");
    e.target.classList.remove('drag-over');
}

function drop(e) {

    const element = e.target;
    if (!element.getAttribute("id") === "defaultFoto" )
    {
        console.log("false", e.target.getAttribute("id"))
        $select_drop_element_clone ='';
        $target_drop_element_clone = '';
        $delete_select_element='';
        return ;
    }


    $parent_targetInsert = e.target.parentElement.parentElement;
    $parent_selectInsert = $delete_select_element.parentElement;


    $target_drop_element_clone = e.target.parentElement.cloneNode(true);
    e.target.classList.remove('drag-over');


    // add it to the drop target


    $parent_targetInsert.appendChild($select_drop_element_clone);
    $parent_selectInsert.appendChild($target_drop_element_clone);

    $parent_targetInsert.removeChild(e.target.parentElement);
    $parent_selectInsert.removeChild($delete_select_element);


    $select_drop_element_clone ='';
    $target_drop_element_clone = '';
    $delete_select_element='';

    // display the draggable element
    //draggable.classList.remove('hide');
}