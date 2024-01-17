<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskmate</title>

    <link rel="icon" href="/public/img/logo.png" />

    <link rel="stylesheet" href="/public/css/main.css">
    <link rel="stylesheet" href="/public/css/form-controls.css">
    <link rel="stylesheet" href="/public/css/main-layout.css">
    <link rel="stylesheet" href="/public//css//entity-panels.css">
    <link rel="stylesheet" href="/public/css/task-n-notes.css">

    <script src="/public/js/stripURL.js"></script>
    <script src="/public/js/main.js" defer></script>
    <script src="/public/js/tasknnotes.js" defer></script>
    <script src="/public/js/modals.js" defer></script>

    <script src="https://kit.fontawesome.com/00b5fcc6a2.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <a href="/home">
            <img src="/public/img/logo.png" alt="Logo">
        </a>
        <button type="button" id="HeaderUserButton" title="User">
            <i class="fa-regular fa-user"></i>
        </button>
    </header>
    <main>
        <nav>
            <a href="#" class="active">
                <i class="fa-solid fa-list"></i>
                <span>Tasks & notes</span>
            </a>
            <a href="/home">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>
            <a href="/group">
                <i class="fa-solid fa-user-group"></i>
                <span>My group</span>
            </a>
        </nav>
        <section class="MainContent">
            <h2>Tasks & notes</h2>
            <section>
                <div class="TargetListSwitcher">
                    <img src="/public/img/list.svg" alt="Tasks" data-list="tasks">
                    <img src="/public/img/note.svg" alt="Notes" data-list="notes">
                    <span class="<?php echo "Show-" . ($activeTab=="t"?"tasks":"notes") ?>"></span>
                </div>
            </section>
            <section class="ListWrapper">
                <section class="EntityList <?php echo ($activeTab!="n"?"Shown":"") ?>" data-list="tasks">
                    <h3>
                        Tasks
                        <button type="button" class="InSectionAddEntityButton" data-type="task">
                            <i class="fa-solid fa-plus"></i>
                            Add new
                        </button>
                    </h3>
                    <?php
                        if(isset($tasks)&&sizeof($tasks) > 0) {
                                echo "<ul>";
                                foreach ($tasks as $task) {
                                    echo "
                                    <li class='EntityPanel Task' data-details='" . json_encode($task, JSON_HEX_APOS) . "'>
                                        <label class='checkbox'>
                                            <input type='checkbox' " . ($task["checkState"]?"checked='1'":"") . ">
                                            <span class='checkmark'></span>
                                        </label>
                                        <div class='TaskBody'>
                                            <h3>" . $task["title"] ."</h3>
                                            <h6>" . (is_null($task["assignedUser"])?"Not assigned":"Assigned to " . $task["assignedUser"]) . 
                                                " <span class='fa-solid fa-circle'></span> " 
                                                . $task["relTime"] 
                                                . (is_null($task["dueDate"])?"":" <span class='fa-solid fa-circle'></span> Due " . $task["dueDate"]) .
                                            "</h6>
                                        </div>
                                    </li>";
                                }
                                echo "</ul>";
                        }else {
                            echo "<div class='NoPanelsNotice'>
                                <h5>No tasks defined yet</h5>
                            </div>";
                        }
                    ?>
                </section>
                <section class="EntityList <?php echo ($activeTab=="n"?"Shown":"") ?>" data-list="notes">
                    <h3>
                        Notes
                        <button type="button" class="InSectionAddEntityButton" data-type="note">
                            <i class="fa-solid fa-plus"></i>
                            Add new
                        </button>
                    </h3>
                    <?php 
                        if(isset($notes)&&sizeof($notes) > 0) {
                            echo "<ul>";
                            foreach ($notes as $note) {
                                echo "
                                <li class='EntityPanel Note' data-details='" . json_encode($note, JSON_HEX_APOS) . "'>
                                    <div class='NoteHeader'>
                                        <h3>" . $note["title"] . "</h3>
                                        <h6>" . $note["relTime"] . "</h6>
                                    </div>
                                    <div class='NoteContent'>
                                        <textarea readonly title='Note'>" . $note["content"] . "</textarea>
                                    </div>
                                </li>";
                            }
                            echo "</ul>";
                        }else {
                            echo "<div class='NoPanelsNotice'>
                                <h5>No notes defined yet</h5>
                            </div>";
                        }
                    ?>
                </section>
            </section>

            <button id="AddEntityButton">
                <i class="fa-solid fa-plus"></i>
            </button>
        </section>
    </main>
    <div id="AccountPopup">
        <div class="PopupHeader">
            <i class="far fa-user"> </i>
            <span><?php echo $signedInUser->getName()?></span>
        </div>
        
        <h4>Your groups <a href="/new">Add new</a></h4>
        <div class="GroupsHolder">
            <?php 
                if(isset($userGroups)&&sizeof($userGroups) > 0){

                    foreach($userGroups as $_group) {
                        $name = $_group->getName();
                        $ID = $_group->getID();
                        echo "<a href='/switchToGroup?target=$ID' class='" . ($_group->getID()==$signedInUser->getActiveGroupID()?"active":"")  . "'>
                                $name
                                <i class='fas fa-check'></i>
                            </a>";
                    }
                }else echo "<p class='NoGroupsMessage'>You don't belong to any group yet.";
            ?>
        </div>
        <a href="/logout">Logout </a>
    </div>
    <div id="CreateModal" class="Modal <?php echo $mode=="c"?"Shown":"" ?>">
        <div class="ModalContainer">
            <div id="CreateTaskModalContent" class="CreateModalContent <?php echo $mode!=""&&$activeTab=="t"?"Shown":""?>">
                <h3>Create new task</h3>
                
                <?php 
                    if($err !== false) {
                        echo "<p class='ErrorBox'>" . $err . "</p>";
                    }
                ?>
                <form action="/createTask" method="POST">
                    <label for="TaskTitleInput">Title</label>
                    <input id="TaskTitleInput" name="title" type="text" required maxlength="50">

                    <label for="AssignedUserInput">Assign</label>
                    <select name="assignedUser" id="AssignedUserInput">
                        <option value="">None</option>
                        <?php 
                            foreach($groupMembers as $member) {
                                echo "<option value='" . $member->getID() ."'>" . $member->getName() . "</option>";
                            }
                        ?>
                    </select>

                    <label for="DueDateInput">Due</label>
                    <input type="date" name="dueDate" id="DueDateInput">

                    <div class="ButtonsWrapper">
                        <button type="reset">Cancel</button>
                        <button type="submit">Create</button>
                    </div>

                </form>
            </div>
            <div id="CreateNoteModalContent" class="CreateModalContent <?php echo $mode!=""&&$activeTab=="n"?"Shown":""?>">
                <h3>Create new note</h3>
                    
                <?php 
                    if($err !== false) {
                        echo "<p class='ErrorBox'>" . $err . "</p>";
                    }
                ?>
                <form action="/createNote" method="POST">
                    <label for="NoteTitleInput">Title</label>
                    <input id="NoteTitleInput" name="title" type="text" required maxlength="50">

                    <label for="ContentInput">Note</label>
                    <textarea name="content" id="ContentInput" maxlength="255" required></textarea>

                    <div class="ButtonsWrapper">
                        <button type="reset">Cancel</button>
                        <button type="submit">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="EditModal" class="Modal <?php echo $mode=="e"?"Shown":"" ?>">
        <div class="ModalContainer">
            <div id="EditTaskModalContent" class="EditModalContent <?php echo $mode!=""&&$activeTab=="t"?"Shown":""?>">
                <h3>Edit task</h3>

                <?php 
                    if($err !== false) {
                        echo "<p class='ErrorBox'>" . $err . "</p>";
                    }
                ?>

                <form action="/deleteTask" method="POST" class="EntityDetails">
                    <input id="Delete_TaskIDInput" name="id" hidden required>
                    <h5>Created by <span id="EditTask_creatorTarget"></span> on <span id="EditTask_createdAtTarget"></span></h5>
                    <h5 id="EditTask_stateTarget"></h5>

                    <div class="ButtonsWrapper">
                        <button type="submit">Delete</button>
                    </div>
                </form>
                
                <form action="/editTask" method="POST">
                    <input id="Edit_TaskIDInput" name="id" hidden required>

                    <label for="Edit_TaskTitleInput">Title</label>
                    <input id="Edit_TaskTitleInput" name="title" type="text" required maxlength="50">

                    <label for="Edit_AssignedUserInput">Assign</label>
                    <select name="assignedUser" id="Edit_AssignedUserInput">
                        <option value="">None</option>
                        <?php 
                            foreach($groupMembers as $member) {
                                echo "<option value='" . $member->getID() ."'>" . $member->getName() . "</option>";
                            }
                        ?>
                    </select>

                    <label for="Edit_DueDateInput">Due</label>
                    <input type="date" name="dueDate" id="Edit_DueDateInput">

                    <div class="ButtonsWrapper">
                        <button type="reset">Cancel</button>
                        <button type="submit">Confirm</button>
                    </div>

                </form>
            </div>
            <div id="EditNoteModalContent" class="EditModalContent <?php echo $mode!=""&&$activeTab=="n"?"Shown":""?>">
                <h3>Edit note</h3>
                    
                <?php 
                    if($err !== false) {
                        echo "<p class='ErrorBox'>" . $err . "</p>";
                    }
                ?>

                <form action="/deleteNote" method="POST" class="EntityDetails">
                    <input id="Delete_NoteIDInput" name="id" hidden required>
                    <h5>Created by <span id="EditNote_creatorTarget"></span> on <span id="EditNote_createdAtTarget"></span></h5>

                    <div class="ButtonsWrapper">
                        <button type="submit">Delete</button>
                    </div>
                </form>

                <form action="/editNote" method="POST">
                    <input id="Edit_NoteIDInput" name="id" hidden required>

                    <label for="Edit_NoteTitleInput">Title</label>
                    <input id="Edit_NoteTitleInput" name="title" type="text" required maxlength="50">

                    <label for="Edit_ContentInput">Note</label>
                    <textarea name="content" id="Edit_ContentInput" maxlength="255" required></textarea>

                    <div class="ButtonsWrapper">
                        <button type="reset">Cancel</button>
                        <button type="submit">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>