window.addEventListener("DOMContentLoaded", (event) => {

    const burger = document.querySelector(".burger");
    const close = document.querySelector(".closebtn");
    const menuResponsive = document.querySelector("#myNav");

    function openNav() {
        document.getElementById("myNav").style.width = "100%";
    }
        
    function closeNav() {
        document.getElementById("myNav").style.width = "0%";
    }

    close.addEventListener("click", closeNav);
    burger.addEventListener("click", openNav);

});