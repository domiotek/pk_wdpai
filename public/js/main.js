
document.querySelector("#HeaderUserButton")?.addEventListener("click",e=>{
    const panel = document.querySelector("#AccountPopup");

    panel?.classList.toggle("shown");
});