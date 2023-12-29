<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskmate</title>

    <link rel="stylesheet" href="/public/css/main.css">
    <link rel="stylesheet" href="/public/css/main-layout.css">
    <link rel="stylesheet" href="/public/css/group.css">
    <link rel="stylesheet" href="/public/css/form-controls.css">

    <script src="/public/js/stripURL.js"></script>
    <script src="/public/js/main.js" defer></script>
    <script src="/public/js/group.js" defer></script>

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
            <a href="/d">
                <i class="fa-solid fa-list"></i>
                <span>Tasks & notes</span>
            </a>
            <a href="/home">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>
            <a href="#" class="active">
                <i class="fa-solid fa-user-group"></i>
                <span>My group</span>
            </a>
        </nav>
        <section class="MainContent">
            <?php if(isset($globalErrMessage)) echo "<p class='ErrorMessageBox'>$globalErrMessage</p>"; ?>
            <h2>Your group</h2>

            <h3>Group name</h3>
            <form class="UpdateNameForm" method="POST" action="changeGroupName">
                <?php
                    if(isset($changeNameErrMessage)) {
                        echo "<p class='ErrorBox'>$changeNameErrMessage</p>";
                    }
                ?>
                <input name="name" type="text" required minLength="2" maxLength="30" value="<?php if(isset($group)) echo $group->getName()?>">
                <button>Update</button>
            </form>

            <h3>Members</h3>
            <section class="UserList">
                <?php 
                    if(isset($groupMembers)) {
                        $isSignedInUserOwner = $signedInUser->getID()==$ownerUserID;

                        $leaveButton = "<a class='button' href='/leaveGroup'>Leave</a>";

                        foreach($groupMembers as $groupMember) {
                            $currentUserID = $groupMember->getID();
                            $kickButton = "<a class='button' href='/kickMember?target=$currentUserID'>Kick</a>";

                            $isSignedInUser = $signedInUser->getID()===$groupMember->getID();
                            $ownsGroup = $groupMember->getID()===$ownerUserID;
                            $name = $isSignedInUser?"You":$groupMember->getName();
                            $emailLine = $isSignedInUser?"":"<h6>" . $groupMember->getEmail() . "</h6>";
                            $organizerTag = $ownsGroup?"(Organizer)":"";
                            
                            if($isSignedInUser) {
                                $button = $leaveButton;
                            }else {
                                $button = $isSignedInUserOwner?$kickButton:"";
                            }     

                            echo "
                            <div class='UserPanel'>
                                <div class='UserImage'>
                                    <i class='fa-regular fa-user'></i>
                                </div>
                                <div class='PanelBody'>
                                    <h3>$name $organizerTag</h3>
                                    $emailLine
                                    $button
                                </div>
                            </div>
                            ";
                        }
                    }
                ?>
            </section>
            <section class="JoinSection">
                <h4>Want to add more people?</h4>
                <h6>Just send them this link</h6>
                <div class="InviteLinkHolder">
                    <h6>http://<?php echo $_SERVER['HTTP_HOST'] ?>/invite?code=<?php echo $group->getInvitationCode()?></h6>
                    <button id="InviteCopyButton"><i class="fa-regular fa-copy"></i></button>
                </div>
                <?php 
                    if($signedInUser->getID()==$ownerUserID)
                        echo "<h6 class='RegenerateCodePrompt'>Want new one? <a href='/regenInvite'>Regenerate</a></h6>";
                ?>
                
            </section>
        </section>
    </main>
    <div id="AccountPopup">
        <div class="PopupHeader">
            <i class="far fa-user"> </i>
            <span>Damian</span>
        </div>
        
        <h4>Your groups <a href="/new">Add new</a></h4>
        <div class="GroupsHolder">
            <?php 
                if(isset($userGroups)&&isset($group)&&sizeof($userGroups) > 0){

                    foreach($userGroups as $_group) {
                        $name = $_group->getName();
                        $ID = $_group->getID();
                        echo "<a href='/switchToGroup?target=$ID' class='" . ($_group->getID()==$group->getID()?"active":"")  . "'>
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