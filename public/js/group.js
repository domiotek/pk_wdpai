document.querySelector("#InviteCopyButton")?.addEventListener("click", e=>{
    let linkSource = document.querySelector(".InviteLinkHolder > h6");

    navigator.clipboard.writeText(linkSource.innerHTML);
});