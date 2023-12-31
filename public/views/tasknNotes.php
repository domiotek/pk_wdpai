<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskmate</title>

    <link rel="stylesheet" href="/public/css/main.css">
    <link rel="stylesheet" href="/public/css/form-controls.css">
    <link rel="stylesheet" href="/public/css/main-layout.css">
    <link rel="stylesheet" href="/public//css//entity-panels.css">
    <link rel="stylesheet" href="/public/css/task-n-notes.css">

    <script src="/public/js/stripURL.js"></script>
    <script src="/public/js/main.js" defer></script>

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
                    <img src="/public/img/list.svg" alt="Tasks">
                    <img src="/public/img/note.svg" alt="Notes">
                    <span></span>
                </div>
            </section>
            <section class="ListWrapper">
                <section class="EntityList" style="display: block;">
                    <h3>
                        Tasks
                        <button type="button" class="InSectionAddEntityButton">
                            <i class="fa-solid fa-plus"></i>
                            Add new
                        </button>
                    </h3>
                    <ul>
                        <li class="EntityPanel Task">
                            <label class="checkbox">
                                <input type="checkbox">
                                <span class="checkmark"></span>
                            </label>
                            <div class="TaskBody">
                                <h3>Do groceries</h3>
                                <h6>Not assigned <span class="fa-solid fa-circle"></span> 1 hour ago</h6>
                            </div>
                        </li>
                        <li class="EntityPanel Task">
                            <label class="checkbox">
                                <input type="checkbox" checked="checked">
                                <span class="checkmark"></span>
                            </label>
                            <div class="TaskBody">
                                <h3>Trash duty</h3>
                                <h6>Andrew <span class="fa-solid fa-circle"></span> 2 hours ago</h6>
                            </div>
                        </li>
                    </ul>
                </section>
                <section class="EntityList">
                    <h3>
                        Notes
                        <button type="button" class="InSectionAddEntityButton">
                            <i class="fa-solid fa-plus"></i>
                            Add new
                        </button>
                    </h3>
                    <ul>
                        <li class="EntityPanel Note">
                            <div class="NoteHeader">
                                <h3>Grocery list</h3>
                                <h6>10 minutes ago</h6>
                            </div>
                            <div class="NoteContent">
                                <textarea readonly title="Note">Milk&#13;&#10;Butter&#13;&#10;Bread</textarea>
                            </div>
                        </li>
                    </ul>
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
</body>
</html>