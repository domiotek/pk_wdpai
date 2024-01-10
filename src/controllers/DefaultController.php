<?php

require_once 'AppController.php';
require_once __DIR__ . "/../repository/GroupRepository.php";
require_once __DIR__ . "/../repository/NoteRepository.php";
require_once __DIR__ . "/../repository/TaskRepository.php";
require_once __DIR__ . "/../repository/UserRepository.php";
require_once __DIR__ . "/../utils.php";

class DefaultController extends AppController {

    public function home() {
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
                $taskStruct["dueDate"] = is_null($task->getDueDate())?null:$task->getDueDate()->format("d/m/y H:i");

                array_push($tasksResult, $taskStruct);
            }

            foreach($notes as $note) {
                $noteStruct = [];
                $relTime = time2str($note->getCreatedAt()->getTimestamp());
                $noteStruct["ID"] = $note->getNoteID();
                $noteStruct["title"] = $note->getTitle();
                $noteStruct["content"] = $note->getContent();
                $noteStruct["relTime"] = $relTime;

                array_push($notesResult, $noteStruct);
            }

            if(sizeof($groups) > 0) {
                $this->render("dashboard", ["userGroups"=>$groups, "signedInUser"=>$user, "notes"=>$notesResult, "tasks"=>$tasksResult]);
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
                $taskStruct["dueDate"] = is_null($task->getDueDate())?null:$task->getDueDate()->format("d/m/y H:i");

                array_push($tasksResult, $taskStruct);
            }

            foreach($notes as $note) {
                $noteStruct = [];
                $relTime = time2str($note->getCreatedAt()->getTimestamp());
                $noteStruct["ID"] = $note->getNoteID();
                $noteStruct["title"] = $note->getTitle();
                $noteStruct["content"] = $note->getContent();
                $noteStruct["relTime"] = $relTime;

                array_push($notesResult, $noteStruct);
            }

            $activeTab = "t";

            if(isset($_REQUEST["t"])) {
                $activeTab = $_REQUEST["t"];
            }

            $this->render("tasknNotes", ["userGroups"=>$groups, "signedInUser"=>$user, "tasks"=>$tasksResult, "notes"=>$notesResult, "activeTab"=>$activeTab]);
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