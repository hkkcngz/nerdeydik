/// Dark / Light Mode
/*
* Kullanımı: 
<?php 
if (kelime_sor( cookie('mode'),'dark-mode') == 1)
    $is_dark = 'dark-mode'; ?>

<body <?php body_class($is_dark); ?>>
*
*/
function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

// Content Share Popup Modal
let btnAddContent = document.getElementById('btn-add-content');
let modalAddContent = document.getElementById('modal-add-content');
btnAddContent.addEventListener('click', function(e) {
    e.preventDefault();
    modalAddContent.classList.toggle("active");
});