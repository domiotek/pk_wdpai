document.querySelectorAll(".TargetListSwitcher > img")?.forEach(elem=>elem.addEventListener("click",e=>{
    document.querySelector(".EntityList.Shown").classList.remove("Shown");
    document.querySelector(`.EntityList[data-list=${e.target.dataset.list}]`).classList.add("Shown");
    document.querySelector(".TargetListSwitcher span").setAttribute("class", `Show-${e.target.dataset.list}`);
}));

