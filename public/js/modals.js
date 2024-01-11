document.querySelectorAll("#CreateModal button[type=reset]").forEach(elem=>elem.addEventListener("click",e=>{
    document.querySelector("#CreateModal").classList.remove("Shown");

    const content = document.querySelector(".CreateModalContent.Shown");

    content.classList.remove("Shown");
}));

document.querySelectorAll("#EditModal button[type=reset]").forEach(elem=>elem.addEventListener("click",e=>{
    document.querySelector("#EditModal").classList.remove("Shown");

    const content = document.querySelector(".EditModalContent.Shown");

    content.classList.remove("Shown");
}));

document.querySelectorAll(".EntityPanel.Task h3").forEach(elem=>elem.addEventListener("click",e=>{
    const editModal = document.querySelector("#EditModal");
    const content = document.querySelector("#EditTaskModalContent");

    try {
        const data = JSON.parse(e.target.closest(".EntityPanel").dataset.details);  

        content.querySelector("#Delete_TaskIDInput").value = data.ID;
        content.querySelector("#Edit_TaskIDInput").value = data.ID;
        content.querySelector("#EditTask_creatorTarget").innerHTML = data.creator;
        content.querySelector("#EditTask_createdAtTarget").innerHTML = data.createdAt;
        content.querySelector("#EditTask_stateTarget").innerHTML = data.checkState?"Completed":"Uncompleted";
        content.querySelector("#Edit_TaskTitleInput").value = data.title;
        content.querySelector("#Edit_AssignedUserInput").value = data.assignedUserID ?? "";
        content.querySelector("#Edit_DueDateInput").value = data.dueDateIso;

        editModal.classList.add("Shown");
        content.classList.add("Shown");
    } catch (error) {
        console.error("Couldn't show modal. " + error.message);
    }
}));

document.querySelectorAll(".EntityPanel.Note .NoteHeader").forEach(elem=>elem.addEventListener("click",e=>{
    const editModal = document.querySelector("#EditModal");
    const content = document.querySelector("#EditNoteModalContent");

    try {
        const data = JSON.parse(e.target.closest(".EntityPanel").dataset.details);  

        content.querySelector("#Delete_NoteIDInput").value = data.ID;
        content.querySelector("#Edit_NoteIDInput").value = data.ID;
        content.querySelector("#EditNote_creatorTarget").innerHTML = data.creator;
        content.querySelector("#EditNote_createdAtTarget").innerHTML = data.createdAt;
        content.querySelector("#Edit_NoteTitleInput").value = data.title;
        content.querySelector("#Edit_ContentInput").value = data.content;

        editModal.classList.add("Shown");
        content.classList.add("Shown");
    } catch (error) {
        console.error("Couldn't show modal. " + error.message);
    }
}));


document.querySelectorAll(".InSectionAddEntityButton").forEach(elem=>elem.addEventListener("click",e=>{
    const type = e.target.dataset.type;

    const createModal = document.querySelector("#CreateModal");

    if(type=="task") {
        createModal.classList.add("Shown");
        const content = createModal.querySelector("#CreateTaskModalContent");
        content.classList.add("Shown");
    }else if(type=="note") {
        createModal.classList.add("Shown");
        const content = createModal.querySelector("#CreateNoteModalContent");
        content.classList.add("Shown");
    }else console.error("Invalid entity list");

}));

document.querySelector("#AddEntityButton")?.addEventListener("click",e=>{
    const activeView = document.querySelector(".EntityList.Shown").dataset.list;

    const createModal = document.querySelector("#CreateModal");

    if(activeView=="tasks") {
        createModal.classList.add("Shown");
        const content = createModal.querySelector("#CreateTaskModalContent");
        content.classList.add("Shown");
    }else if(activeView=="notes") {
        createModal.classList.add("Shown");
        const content = createModal.querySelector("#CreateNoteModalContent");
        content.classList.add("Shown");
    }else console.error("Invalid entity list");
});