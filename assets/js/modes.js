/// MODES

// Dark Mode
const toggleDark = document.querySelector(".do-dark-mode");

toggleDark.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");
    setCookie('mode', document.body.classList, 99999);
});

// Rainy Mode  makeItRain();
const toggleRainy = document.querySelector(".do-rainy-mode");

toggleRainy.addEventListener("click", () => {
    document.body.classList.toggle("rainy-mode");
    setCookie('mode', document.body.classList, 99999);
});

// Spring Mode
const toggleSpring = document.querySelector(".do-spring-mode");

toggleSpring.addEventListener("click", () => {
    document.body.classList.toggle("spring-mode");
    setCookie('mode', document.body.classList, 99999);
});

/* start up leaf scene
  var leafContainer = document.querySelector('.falling-leaves');
      if( leafContainer ) {
        leaves = new LeafScene(leafContainer);

        leaves.init();
        leaves.render();
      }
*/


// On Hover Play Videos
const allVideos = document.querySelectorAll(".video");

allVideos.forEach((v) => {
    v.addEventListener("mouseover", () => {
        const video = v.querySelector("video");
        video.play();
    });
    v.addEventListener("mouseleave", () => {
        const video = v.querySelector("video");
        video.pause();
    });
});


/* Let's Search!
$(function () {
    $("#btn-search").click(function () {
        $("#modal-search").modal("toggle");
    });

});
// Let's Search! */