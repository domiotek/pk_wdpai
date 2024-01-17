<?php

require_once 'AppController.php';
require_once __DIR__ . "/../repository/GroupRepository.php";
require_once __DIR__ . "/../repository/NoteRepository.php";
require_once __DIR__ . "/../repository/TaskRepository.php";
require_once __DIR__ . "/../repository/UserRepository.php";
require_once __DIR__ . "/../repository/EventRepository.php";
require_once __DIR__ . "/../utils.php";

class DefaultController extends AppController {

    private function prepateTaskResults(array $tasks, UserRepository $userRep) {
        $result = [];

        foreach($tasks as $task) {
            $relTime = time2str($task->getCreatedAt()->getTimestamp());
            $assignedUser = is_null($task->getAssignedUserID())?null:$userRep->getUser($task->getAssignedUserID())->getName();

            array_push($result,[
                "ID"=>$task->getTaskID(),
                "title"=>$task->getTitle(),
                "checkState"=>$task->getIsCompleted(),
                "relTime"=> $relTime,
                "assignedUser"=>$assignedUser,
                "assignedUserID"=>$task->getAssignedUserID(),
                "dueDate"=> is_null($task->getDueDate())?null:$task->getDueDate()->format("d/m/y"),
                "dueDateIso"=> is_null($task->getDueDate())?null:$task->getDueDate()->format("Y-m-d"),
                "createdAt"=>$task->getCreatedAt()->format("d/m/y H:i"),
                "creator"=>$userRep->getUser($task->getCreatorUserID())->getName()
            ]);
        }

        return $result;
    }

    public function prepareNoteResults(array $notes, UserRepository $userRep) {
        $result = [];

        foreach($notes as $note) {
            $relTime = time2str($note->getCreatedAt()->getTimestamp());
            
            array_push($result, [
                "ID"=> $note->getNoteID(),
                "title"=> $note->getTitle(),
                "content"=> $note->getContent(),
                "relTime"=> $relTime,
                "createdAt"=> $note->getCreatedAt()->format("d/m/y H:i"),
                "creator"=> $userRep->getUser($note->getCreatorUserID())->getName()
            ]);
        }

        return $result;
    }

    public function home() {
        if($this->isAuthenticated()) {
            $signedInUser = $this->getSignedInUserID();
        
            $groupsRep = new GroupRepository();
            $notesRep = new NoteRepository();
            $tasksRep = new TaskRepository();
            $userRep = new UserRepository();
            $eventRep = new EventRepository();

            $groups = $groupsRep->getUserGroups($signedInUser);

            if(sizeof($groups) > 0) {
                $activeGroup = $groupsRep->getGroup($signedInUser->getActiveGroupID());

                $notes = $notesRep->getNotes($activeGroup,3);
                $tasks = $tasksRep->getTasks($activeGroup,3);
                $events = $eventRep->getAllEvents($activeGroup);
    
                $tasksResult = $this->prepateTaskResults($tasks, $userRep);
                $notesResult = $this->prepareNoteResults($notes, $userRep);

                $eventsResult = [];
    
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

                $groupMembers = $groupsRep->getGroupMembers($activeGroup);

                $this->render("dashboard", ["userGroups"=>$groups, "signedInUser"=>$signedInUser, "notes"=>$notesResult, "tasks"=>$tasksResult, "groupMembers"=>$groupMembers, "events"=>$eventsResult]);

            } else {
                $this->render("addGroup",["subtitle"=>"You don't belong to any group yet.", "userGroups"=>[], "signedInUser"=>$signedInUser]);
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

            $notes = $notesRep->getNotes($activeGroup);
            $tasks = $tasksRep->getTasks($activeGroup);

            $tasksResult = $this->prepateTaskResults($tasks, $userRep);
            $notesResult = $this->prepareNoteResults($notes, $userRep);

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