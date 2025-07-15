

/*NAVIGATION*/
let isHome = true;
let isCourse = false;
let isProfile = false;

function goToHome(){
    window.location.href = "home.html";
    isHome = true;
    isCourse = false;
    isProfile = false;
    efectNav();
}
function goToCourse() {
    window.location.href = "./course.php";
    isHome = false;
    isCourse = true;
    isProfile = false;

}
function goToProfile(){
    window.location.href = "./profile.php";
    isHome = false;
    isCourse = false;
    isProfile = false;

}
function efectNav(){
    if(isHome){
        document.getElementById('homeDiv').innerHTML;
    }
}


/*Slideshow*/let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    let i;
    let slides = document.getElementsByClassName("mySlides");
    let dots = document.getElementsByClassName("dot");
    if (n > slides.length) { slideIndex = 1 }
    if (n < 1) { slideIndex = slides.length }
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex - 1].style.display = "block";
    dots[slideIndex - 1].className += " active";
}

function goToAnmelden() {
    window.location.href = "./anmelden.php";
}

function goToRegistrieren() {
    window.location.href = "./register.php";
}

