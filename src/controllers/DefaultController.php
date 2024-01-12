<?php

require_once 'AppController.php';
require_once __DIR__ . "/../repository/GroupRepository.php";
require_once __DIR__ . "/../repository/NoteRepository.php";
require_once __DIR__ . "/../repository/TaskRepository.php";
require_once __DIR__ . "/../repository/UserRepository.php";
require_once __DIR__ . "/../repository/EventRepository.php";
require_once __DIR__ . "/../utils.php";

class DefaultController extends AppController {

    public function home() {
        if($this->isAuthenticated()) {
            $user = $this->getSignedInUserID();
        
            $groupsRep = new GroupRepository();
            $notesRep = new NoteRepository();
            $tasksRep = new TaskRepository();
            $userRep = new UserRepository();
            $eventRep = new EventRepository();

            $groups = $groupsRep->getUserGroups($user);
            $activeGroup = $groupsRep->getGroup($user->getActiveGroupID());

            $notes = $notesRep->getAllNotes($activeGroup);
            $tasks = $tasksRep->getAllTasks($activeGroup);
            $events = $eventRep->getAllEvents($activeGroup);
            $tasksResult = [];
            $notesResult = [];
            $eventsResult = [];

            foreach($tasks as $task) {
                $taskStruct = [];

                $relTime = time2str($task->getCreatedAt()->getTimestamp());
                $assignedUser = is_null($task->getAssignedUserID())?null:$userRep->getUser($task->getAssignedUserID())->getName();
                
                $taskStruct["ID"] = $task->getTaskID();
                $taskStruct["title"] = $task->getTitle();
                $taskStruct["checkState"] = $task->getIsCompleted();
                $taskStruct["relTime"] = $relTime;
                $taskStruct["assignedUser"] = $assignedUser;
                $taskStruct["assignedUserID"] = $task->getAssignedUserID();
                $taskStruct["dueDate"] = is_null($task->getDueDate())?null:$task->getDueDate()->format("d/m/y");
                $taskStruct["dueDateIso"] = is_null($task->getDueDate())?null:$task->getDueDate()->format("Y-m-d");
                $taskStruct["createdAt"] = $task->getCreatedAt()->format("d/m/y H:i");
                $taskStruct["creator"] = $userRep->getUser($task->getCreatorUserID())->getName();

                array_push($tasksResult, $taskStruct);
            }

            foreach($notes as $note) {
                $noteStruct = [];
                $relTime = time2str($note->getCreatedAt()->getTimestamp());
                $noteStruct["ID"] = $note->getNoteID();
                $noteStruct["title"] = $note->getTitle();
                $noteStruct["content"] = $note->getContent();
                $noteStruct["relTime"] = $relTime;
                $noteStruct["createdAt"] = $note->getCreatedAt()->format("d/m/y H:i");
                $noteStruct["creator"] = $userRep->getUser($note->getCreatorUserID())->getName();

                array_push($notesResult, $noteStruct);
            }

            foreach($events as $event) {
                $header = "";
                $content = "";
                $iconURL = "";

                $user = $userRep->getUser($event->getInitiatorID());

                $target = $event->getTargetType()=="task"?$tasksRep->getTaskByObjectID($event->getTargetID()) : $notesRep->getNoteByObjectID($event->getTargetID());

                switch($event->getEventType()) {
                    case "create": 
                        $header = $user->getName() . " created a new " . $event->getTargetType();
                        $content = $target->getTitle();
                        $iconURL = "/public/img/entity-added.svg";
                    break;
                    case "update":
                        $header = $user->getName() . " updated " . $event->getTargetType();
                        $content = $target->getTitle();
                        $iconURL = "/public/img/entity-edited.svg";
                    break;
                    case "complete":
                        $header = $user->getName() . " marked task as complete";
                        $content = $target->getTitle();
                        $iconURL = "/public/img/task-completed.svg";
                    break;
                    case "uncomplete":
                        $header = $user->getName() . " marked task as incomplete";
                        $content = $target->getTitle();
                        $iconURL = "/public/img/task-uncompleted.svg";
                    break;
                }

                array_push($eventsResult, [
                    "header"=> $header,
                    "content"=> $content,
                    "relTime"=> time2str($event->getWhen()->getTimestamp()),
                    "iconURL"=> $iconURL
                ]);
            }

            if(sizeof($groups) > 0) {
                
                $groupMembers = $groupsRep->getGroupMembers($activeGroup);

                $this->render("dashboard", ["userGroups"=>$groups, "signedInUser"=>$user, "notes"=>$notesResult, "tasks"=>$tasksResult, "groupMembers"=>$groupMembers, "events"=>$eventsResult]);
            } else {
                $this->render("addGroup",["subtitle"=>"You don't belong to any group yet.", "userGroups"=>[], "signedInUser"=>$user]);
            }
        }else {
            $this->redirect("login?r=session_expired");
        }
    }

    public function new() {

        if($this->isAuthenticated()) {
            $user = $this->getSignedInUserID();
        
            $groupsRep = new GroupRepository();

            $groups = $groupsRep->getUserGroups($user);

            $message = false;
            $type = "none";
            if(isset($_REQUEST["r"])) {
                switch($_REQUEST["r"]) {
                    case "cInvName": $type = "createErrMessage"; $message = "Invalid group name."; break;
                    case "cMaxGrp": $type = "createErrMessage"; $message = "You already own maximum of 3 groups."; break;
                    case "jInvCode": $type = "joinErrMessage"; $message = "Invalid invitation code."; break;
                    case "jAlrMemb": $type = "joinErrMessage"; $message = "You are already a member of that group."; break;
                }
            }

            $this->render("addGroup", ["subtitle"=>"Want to explore something new?", "userGroups"=>$groups, "signedInUser"=>$user, $type=>$message!==false?$message:null]);
        }else {
            $this->redirect("login?r=session_expired");
        }
    }

    public function d() {
        if($this->isAuthenticated()) {
            $user = $this->getSignedInUserID();
        
            $groupsRep = new GroupRepository();
            $notesRep = new NoteRepository();
            $tasksRep = new TaskRepository();
            $userRep = new UserRepository();

            $groups = $groupsRep->getUserGroups($user);
            $activeGroup = $groupsRep->getGroup($user->getActiveGroupID());

            $notes = $notesRep->getAllNotes($activeGroup);
            $tasks = $tasksRep->getAllTasks($activeGroup);
            $tasksResult = [];
            $notesResult = [];

            foreach($tasks as $task) {
                $taskStruct = [];

                $relTime = time2str($task->getCreatedAt()->getTimestamp());
                $assignedUser = is_null($task->getAssignedUserID())?null:$userRep->getUser($task->getAssignedUserID())->getName();
                
                $taskStruct["ID"] = $task->getTaskID();
                $taskStruct["title"] = $task->getTitle();
                $taskStruct["checkState"] = $task->getIsCompleted();
                $taskStruct["relTime"] = $relTime;
                $taskStruct["assignedUser"] = $assignedUser;
                $taskStruct["assignedUserID"] = $task->getAssignedUserID();
                $taskStruct["dueDate"] = is_null($task->getDueDate())?null:$task->getDueDate()->format("d/m/y");
                $taskStruct["dueDateIso"] = is_null($task->getDueDate())?null:$task->getDueDate()->format("Y-m-d");
                $taskStruct["createdAt"] = $task->getCreatedAt()->format("d/m/y H:i");
                $taskStruct["creator"] = $userRep->getUser($task->getCreatorUserID())->getName();

                array_push($tasksResult, $taskStruct);
            }

            foreach($notes as $note) {
                $noteStruct = [];
                $relTime = time2str($note->getCreatedAt()->getTimestamp());
                $noteStruct["ID"] = $note->getNoteID();
                $noteStruct["title"] = $note->getTitle();
                $noteStruct["content"] = $note->getContent();
                $noteStruct["relTime"] = $relTime;
                $noteStruct["createdAt"] = $note->getCreatedAt()->format("d/m/y H:i");
                $noteStruct["creator"] = $userRep->getUser($note->getCreatorUserID())->getName();

                array_push($notesResult, $noteStruct);
            }

            $activeTab = "t";

            if(isset($_REQUEST["t"])) {
                $activeTab = $_REQUEST["t"];
            }

            $errMessage = false;
            if(isset($_REQUEST["r"])) {
                switch($_REQUEST["r"]) {
                    case "invTitle": $errMessage = "Invalid title."; break;
                    case "noUser": $errMessage = "Couldn't find specified user."; break;
                    case "notMember": $errMessage = "Specified user is not a member of this group."; break;
                    case "pastDate": $errMessage = "Given date from the past."; break;
                    case "invContent": $errMessage = "Invalid note content."; break;
                    case "noEntity": $errMessage = "Couldn't find requested object."; break;
                }
            }

            $mode = ""; //c - create, e - edit

            if(isset($_REQUEST["m"])) {
                $mode = $_REQUEST["m"];
            }

            $groupMembers = $groupsRep->getGroupMembers($activeGroup);

            $this->render("tasknNotes", ["userGroups"=>$groups, "signedInUser"=>$user, "tasks"=>$tasksResult, "notes"=>$notesResult, "groupMembers"=>$groupMembers, "activeTab"=>$activeTab, "err"=>$errMessage, "mode"=>$mode]);
        }else {
            $this->redirect("login?r=session_expired");
        }
    }

    public function group() {
        if($this->isAuthenticated()) {
            $user = $this->getSignedInUserID();
        
            $groupsRep = new GroupRepository();

            $groups = $groupsRep->getUserGroups($user);

            $activeGroupID = $user->getActiveGroupID();
            if($activeGroupID == null) {
                $this->redirect("home");
            }
        
            $activeGroup = $groupsRep->getGroup($activeGroupID);

            $errMessage = false;
            if(isset($_REQUEST["r"])) {
                switch($_REQUEST["r"]) {
                    case "noGroup": $errMessage = "Internal errror. Group doesn't exist ??"; break;
                    case "noMember": $errMessage = "That user doesn't belong to that group."; break;
                    case "noMemberYou": $errMessage = "You don't belong to that group."; break;
                    case "noUser": $errMessage = "That user doesn't exist anymore."; break;
                    case "notOwner": $errMessage = "You are not the owner of the group."; break;
                    case "cky": $errMessage = "You can't kick yourself."; break;
                    case "invName": $errMessage = "Invalid group name."; break;
                    case "generic": $errMessage = "Unexpected error. Try again in a moment.";
                }
            }

            $this->render("group", [
                "signedInUser"=>$user,
                "ownerUserID"=>$activeGroup->getOwnerUserID(),
                "userGroups"=>$groups, 
                "group"=>$activeGroup, 
                "groupMembers"=> $groupsRep->getGroupMembers($activeGroup),
                "globalErrMessage"=> $errMessage!==false?"Couldn't perform that request. " . $errMessage:null
            ]);
        }else {
            $this->redirect("login?r=session_expired");
        }
    }
}