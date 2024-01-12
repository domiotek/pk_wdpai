document.querySelectorAll(".EventCardCarousel button, .EventCardCarousel button i").forEach(elem=>elem.addEventListener("click",e=>{
    const button = e.target.localName=="i"?e.target.parentElement:e.target;

    const direction = button.dataset.direction=="left"?-1:1;

    const carousel = document.querySelector(".EventCardCarousel > div");

    carousel.scrollLeft = carousel.scrollLeft + direction * 200;
}));