/**
     * Modal for image preview
     */
const img = document.getElementById("modal-image");
const modalTitle = document.getElementById("modal-title");
const simpleModal = document.getElementById("simple-modal");
var modalPreloader;
simpleModal.addEventListener("show.bs.modal", (e) => {
    modalPreloader = showPreloader();
    simpleModal.style.visibility = "hidden";
    const bigImage = e.relatedTarget.getAttribute("data-bigimage");
    const title = e.relatedTarget.getAttribute("data-title");
    img.src = bigImage;
    modalTitle.innerHTML = title;  
});
img.addEventListener("load", () => {
  modalPreloader.remove();
  simpleModal.style.visibility = "visible";
})



function showPreloader() {
  const preloader = document.createElement("div");
  preloader.setAttribute("id", "preloader");
  preloader.setAttribute("class", "preloader-transparent");
  document.body.appendChild(preloader);
  return preloader;
}


/**
 * Initialize splide
 */
const splide = new Splide(".splide", {
    type: "loop",
    autoplay: false,
    drag: "false",
    snap: true,
    focus: "center",
    perPage: 1,
    padding: "1vw",
    gap: "2vw",
    mediaQuery: "min",
    breakpoints: {
        576: {
            perPage: 2,
            padding: "1vw",
            gap: "2vw",
        },
    },
}).mount(window.splide.Extensions);

splide.on("click", function (slide, e) {
    splide.go(slide.index);
});