<?php

require_once "AppController.php";

class FormController extends AppController {

    public function createGroup() {
        if(!$this->isAuthenticated() || !$this->isPost()) {
            $this->redirect("new");
        }

        $user = $this->getSignedInUserID();

        $groupRepository = new GroupRepository();
        $userRepository = new UserRepository();

        $name = $_POST["name"];
        $userGroups = $groupRepository->getUserOwnedGroups($user);


        if(strlen($name) < 2 || strlen($name) > 30 || sizeof($userGroups) >= 3) {
            $errCode = sizeof($userGroups) >= 3?"cMaxGrp":"cInvName";

            $this->redirect("new?r=$errCode");
            return;
        }

        $group = $groupRepository->createGroup($name);
        $groupRepository->addGroupMember($group, $user);

        $group->setOwnerUserID($user->getID());

        $groupRepository->updateGroup($group);

        $user->setActiveGroupID($group->getID());

        $userRepository->updateUser($user);

        $this->redirect("home");
    }

    public function joinGroup() {
        if(!$this->isAuthenticated() || !$this->isPost()) {
            $this->redirect("new");
        }

        if(!isset($_POST["code"])) { 
            $this->redirect("new");
        }

        $code = $_POST["code"];
        $user = $this->getSignedInUserID();
        $groupRepository = new GroupRepository();
        $userRepository = new UserRepository();

        $group = $groupRepository->getGroupByInvite($code);

        if($group==null || $groupRepository->isGroupMember($user, $group)) {
            $errCode = $group==null?"jInvCode":"jAlrMemb";

            $this->redirect("new?r=$errCode");
            return;
            
        }

        $groupRepository->addGroupMember($group, $user);
        if($user->getActiveGroupID()==null) {
            $user->setActiveGroupID($group->getID());
            $userRepository->updateUser($user);
        }
        $this->redirect("home");
    }

    public function switchToGroup() {
        if(!$this->isAuthenticated() || !isset($_REQUEST["target"])) {
            $this->redirect("home");
        }

        $ID = $_REQUEST["target"];
        $user = $this->getSignedInUserID();
        $groupRepository = new GroupRepository();
        $userRepository = new UserRepository();

        $group = $groupRepository->getGroup($ID);

        if($group==null || !$groupRepository->isGroupMember($user, $group)) {
            $this->redirect("home");
        }

        $user->setActiveGroupID($group->getID());
        $userRepository->updateUser($user);

        $this->redirect("home");
    }

    public function changeGroupName() {
        if(!$this->isAuthenticated() || !isset($_REQUEST["name"])) {
            $this->redirect("home");
        }

        if(!$this->isPost()) {
            $this->redirect("group");
        }

        $newName = $_REQUEST["name"];
        $user = $this->getSignedInUserID();
        $groupRepository = new GroupRepository();

        $group = $groupRepository->getGroup($user->getActiveGroupID());

        if($group==null ||strlen($newName) < 2 || strlen($newName) > 30) {
            $errCode = $group==null?"generic":"invName";

            $this->redirect("group?r=$errCode");
            return;
        }

        $group->setName($newName);
        $groupRepository->updateGroup($group);

        $this->redirect("group");
    }

    public function kickMember() {
        if(!$this->isAuthenticated()||!isset($_REQUEST["target"])) {
            $this->redirect("home");
        }

        $initiator = $this->getSignedInUserID();
        $groupRepository = new GroupRepository();
        $userRepository = new UserRepository();


        $targetUser = $userRepository->getUser((int)$_REQUEST["target"]);
        $targetGroup = $groupRepository->getGroup($initiator->getActiveGroupID());

        $err = true;
        $errCode = "";
        switch(true) {
            case $targetGroup==null: $errCode = "noGroup"; break;
            case $targetUser==null; $errCode = "noUser"; break;
            case !$groupRepository->isGroupMember($targetUser, $targetGroup): $errCode="noMember"; break;
            case $targetGroup->getOwnerUserID()!=$initiator->getID(): $errCode = "notOwner"; break;
            case $targetUser->getID()==$initiator->getID(): $errCode = "cky"; break;
            default: $err=false;
        }

        if($err) {
            $this->redirect("group?r=$errCode");
        }

        $groupRepository->removeGroupMember($targetGroup, $targetUser);
        if($targetUser->getActiveGroupID()==$targetGroup->getID()) {
            $groups = $groupRepository->getUserGroups($targetUser);
            if(sizeof($groups) > 0) {
                $targetUser->setActiveGroupID($groups[0]->getID());
            }else $targetUser->setActiveGroupID(null);

            $userRepository->updateUser($targetUser);
        }

        $this->redirect("group");
    }

    public function leaveGroup() {
        if(!$this->isAuthenticated()) {
            $this->redirect("home");
        }

        $user = $this->getSignedInUserID();
        $groupRepository = new GroupRepository();
        $userRepository = new UserRepository();

        $targetGroup = $groupRepository->getGroup($user->getActiveGroupID());

        $err = true;
        $errCode = "";
        switch(true) {
            case $targetGroup==null: $errCode = "noGroup"; break;
            case !$groupRepository->isGroupMember($user, $targetGroup): $errCode = "noMemberYou"; break;
            default: $err=false;
        }

        if($err) {
            $this->redirect("group?r=$errCode");
        }

        $groupRepository->removeGroupMember($targetGroup, $user);
        if($user->getActiveGroupID()==$targetGroup->getID()) {
            $groups = $groupRepository->getUserGroups($user);
            if(sizeof($groups) > 0) {
                $user->setActiveGroupID($groups[0]->getID());
            }else $user->setActiveGroupID(null);

            $userRepository->updateUser($user);
        }

        if($targetGroup->getOwnerUserID()==$user->getID()) {
            $members = $groupRepository->getGroupMembers($targetGroup);

            if(sizeof($members) > 0) {
                $targetGroup->setOwnerUserID($members[0]->getID());
                $groupRepository->updateGroup($targetGroup);
            }else {
                $groupRepository->deleteGroup($targetGroup);
            }
        }

        $this->redirect("group");
    }

    public function regenInvite() {
        if(!$this->isAuthenticated()) {
            $this->redirect("home");
        }

        $user = $this->getSignedInUserID();
        $groupRepository = new GroupRepository();


        $targetGroup = $groupRepository->getGroup($user->getActiveGroupID());

        $err = true;
        $errCode = "";
        switch(true) {
            case $targetGroup==null: $errCode = "noGroup"; break;
            case $targetGroup->getOwnerUserID()!=$user->getID(): $errCode = "notOwner"; break;
            default: $err=false;
        }

        if($err) {
            $this->redirect("group?r=$errCode");
        }

        $targetGroup->setInvitationCode(bin2hex(random_bytes(3)));
        $groupRepository->updateGroup($targetGroup);

        $this->redirect("group");
    }

    public function invite() {
        if(!$this->isAuthenticated() || !isset($_REQUEST["code"])) {
            $this->redirect("new");
        }

        $code = $_REQUEST["code"];
        $user = $this->getSignedInUserID();
        $groupRepository = new GroupRepository();
        $userRepository = new UserRepository();

        $group = $groupRepository->getGroupByInvite($code);

        if($group==null || $groupRepository->isGroupMember($user, $group)) {
            $errCode = $group==null?"jInvCode":"jAlrMemb";

            $this->redirect("new?r=$errCode");
            return;
            
        }

        $groupRepository->addGroupMember($group, $user);
        if($user->getActiveGroupID()==null) {
            $user->setActiveGroupID($group->getID());
            $userRepository->updateUser($user);
        }
        $this->redirect("home");

    }

    public function createTask() {
        if(!$this->isAuthenticated() || !isset($_POST["title"]) | !isset($_POST["assignedUser"]) | !isset($_POST["dueDate"])) {
            $this->redirect("d?t=t");
        }

        $title = $_POST["title"];
        $assign = $_POST["assignedUser"];
        $dueDate = $_POST["dueDate"];
        $user = $this->getSignedInUserID();
        $userRep = new UserRepository();
        $groupRep = new GroupRepository();
        $currGroup = $groupRep->getGroup($user->getActiveGroupID());

        $titleLen = strlen($title);

        if($titleLen == 0 || $titleLen > 50) {
            $this->redirect("/d?t=t&m=c&r=invTitle");
        }

        $readyAssingedUser = null;

        if($assign!="") {
            $readyAssingedUser = $userRep->getUser(intval($assign));
            if(!$readyAssingedUser) {
                $this->redirect("/d?t=t&m=c&r=noUser");
            }

            if(!$groupRep->isGroupMember($readyAssingedUser,$currGroup)) {
                $this->redirect("/d?t=t&m=c&r=notMember");
            }
        }

        if($dueDate!="") {
            $date = new DateTime($dueDate);

            if($date < new DateTime("now")) {
                $this->redirect("/d?t=t&m=c&r=pastDate");
            }

            $dueDate = $date;
        }else $dueDate = null;

        $taskRep = new TaskRepository();

        if($task = $taskRep->createTask($title, $currGroup, $user, $readyAssingedUser, $dueDate)) {
            $eventRep = new EventRepository();
            $events = $eventRep->getAllEvents($currGroup);
            $eventRep->trimEventsCount($events);

            $eventRep->createEvent($user,$currGroup,"create",$task);
        }

        $this->redirect("d?t=t");
    }
    
    public function createNote() {
        if(!$this->isAuthenticated() || !isset($_POST["title"]) | !isset($_POST["content"])) {
            $this->redirect("d?t=n");
        }

        $title = $_POST["title"];
        $content = $_POST["content"];
        $user = $this->getSignedInUserID();
        $groupRep = new GroupRepository();
        $currGroup = $groupRep->getGroup($user->getActiveGroupID());

        $titleLen = strlen($title);

        if($titleLen == 0 || $titleLen > 50) {
            $this->redirect("/d?t=n&m=c&r=invTitle");
        }

        $contentLen = strlen($content);

        if($contentLen == 0 || $contentLen > 255) {
            $this->redirect("/d?t=n&m=c&r=intContent");
        }

        $noteRep = new NoteRepository();

        if($note = $noteRep->createNote($title, $currGroup, $user, $content)) {
            $eventRep = new EventRepository();
            $events = $eventRep->getAllEvents($currGroup);
            $eventRep->trimEventsCount($events);

            $eventRep->createEvent($user,$currGroup,"create",$note);
        }

        $this->redirect("d?t=n");
    }

    public function editTask() {
        if(!$this->isAuthenticated() || !isset($_POST["id"]) || !isset($_POST["title"]) || !isset($_POST["assignedUser"]) || !isset($_POST["dueDate"])) {
            $this->redirect("d?t=t");
        }

        $ID = $_POST["id"];
        $title = $_POST["title"];
        $assign = $_POST["assignedUser"];
        $dueDate = $_POST["dueDate"];
        $user = $this->getSignedInUserID();
        $userRep = new UserRepository();
        $groupRep = new GroupRepository();
        $taskRep = new TaskRepository();
        $currGroup = $groupRep->getGroup($user->getActiveGroupID());

        $task = $taskRep->getTask($ID);

        if($task===null) {
            $this->redirect("/d?t=t&m=e&r=NoEntity");
        }

        $titleLen = strlen($title);

        if($titleLen == 0 || $titleLen > 50) {
            $this->redirect("/d?t=t&m=e&r=invTitle");
        }

        $task->setTitle($title);

        $readyAssingedUser = null;

        if($assign!="") {
            $readyAssingedUser = $userRep->getUser(intval($assign));
            if(!$readyAssingedUser) {
                $this->redirect("/d?t=t&m=e&r=noUser");
            }

            if(!$groupRep->isGroupMember($readyAssingedUser,$currGroup)) {
                $this->redirect("/d?t=t&m=e&r=notMember");
            }

            $readyAssingedUser = $readyAssingedUser->getID();
        }

        $task->setAssignedUserID($readyAssingedUser);

        if($dueDate!="") {
            $date = new DateTime($dueDate);

            if($date < new DateTime("now")) {
                $this->redirect("/d?t=t&m=e&r=pastDate");
            }

            $dueDate = $date;
        }else $dueDate = null;

        $task->setDueDate($dueDate);

        $taskRep = new TaskRepository();

        $taskRep->updateTask($task);

        $eventRep = new EventRepository();
        $events = $eventRep->getAllEvents($currGroup);
        $eventRep->trimEventsCount($events);

        $eventRep->createEvent($user, $currGroup,"update", $task);

        $this->redirect("d?t=t");
    }

    public function editNote() {
        if(!$this->isAuthenticated() || !isset($_POST["id"]) || !isset($_POST["title"]) || !isset($_POST["content"])) {
            $this->redirect("d?t=n");
        }

        $ID = $_POST["id"];
        $title = $_POST["title"];
        $content = $_POST["content"];
        $noteRep = new NoteRepository();

        $note = $noteRep->getNote($ID);

        if($note===null) {
            $this->redirect("/d?t=n&m=e&r=NoEntity");
        }

        $titleLen = strlen($title);

        if($titleLen == 0 || $titleLen > 50) {
            $this->redirect("/d?t=t&m=e&r=invTitle");
        }

        $note->setTitle($title);

        $contentLen = strlen($content);

        if($contentLen == 0 || $contentLen > 255) {
            $this->redirect("/d?t=n&m=e&r=intContent");
        }

        $note->setContent($content);

        $noteRep->updateNote($note);

        $user = $this->getSignedInUserID();
        $groupRep = new GroupRepository();
        $currGroup = $groupRep->getGroup($user->getActiveGroupID());

        $eventRep = new EventRepository();
        $events = $eventRep->getAllEvents($currGroup);
        $eventRep->trimEventsCount($events);

        $eventRep->createEvent($user, $currGroup,"update", $note);

        $this->redirect("d?t=n");
    }

    public function deleteTask() {
        if(!$this->isAuthenticated() || !isset($_POST["id"])) {
            $this->redirect("d?t=t");
        }

        $taskRep = new TaskRepository();

        $task = $taskRep->getTask($_POST["id"]);

        if($task) {
            $taskRep->deleteTask($task);
        };

        $this->redirect("d?t=t");
    }

    public function deleteNote() {
        if(!$this->isAuthenticated() || !isset($_POST["id"])) {
            $this->redirect("d?t=n");
        }

        $noteRep = new NoteRepository();

        $note = $noteRep->getNote($_POST["id"]);

        if($note) {
            $noteRep->deleteNote($note);
        };

        $this->redirect("d?t=n");
    }
}