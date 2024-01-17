<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskmate</title>

    <link rel="stylesheet" href="/public/css/main.css">
    <link rel="stylesheet" href="/public/css/form-controls.css">
    <link rel="stylesheet" href="/public/css/add-group.css">

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
        <h2 class="title">Hello <?php echo $signedInUser->getName()?></h2>
        <h5 class="subtitle">
            <?php 
                if(isset($subtitle)) {
                    echo $subtitle;
                }
            ?>
         </h5>

        <div class="SectionsWrapper">
            <section>
                <h3>Join existing group</h3>
                <form method="POST" action="/joinGroup">
                    <?php
                        if(isset($joinErrMessage)) {
                            echo "<p class='ErrorBox'>$joinErrMessage</p>";
                        }
                    ?>
                    
                    <label for="joinCodeInput">Code</label>
                    <input id="joinCodeInput" name="code" type="text" required>
                    <button type="submit">Join</button>
                </form>
            </section>
            <h3 class="SectionDivider">Or</h3>
            <section>
                <h3>Create new group</h3>
                <form method="POST" action="/createGroup">
                    <?php
                        if(isset($createErrMessage)) {
                            echo "<p class='ErrorBox'>$createErrMessage</p>";
                        }
                    ?>

                    <label for="groupNameInput">Group name</label>
                    <input id="groupNameInput" name="name" type="text" minlength="2" maxlength="30" required>
                    <button type="submit">Create</button>
                </form>
            </section>
        </div>
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