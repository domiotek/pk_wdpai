<?php

require_once 'AppController.php';
require_once __DIR__ . "/../repository/GroupRepository.php";

class DefaultController extends AppController {

    public function home() {
        if($this->isAuthenticated()) {
            $user = $this->getSignedInUserID();
        
            $groupsRep = new GroupRepository();

            $groups = $groupsRep->getUserGroups($user);

            if(sizeof($groups) > 0) {
                $this->render("dashboard", ["userGroups"=>$groups, "activeGroupID"=>$user->getActiveGroupID()]);
            } else {
                $this->render("addGroup",["subtitle"=>"You don't belong to any group yet.", "userGroups"=>[]]);
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

            $this->render("addGroup", ["subtitle"=>"Want to explore something new?", "userGroups"=>$groups, "activeGroupID"=>$user->getActiveGroupID(), $type=>$message!==false?$message:null]);
        }else {
            $this->redirect("login?r=session_expired");
        }
    }

    public function d() {
        if($this->isAuthenticated()) {
            $user = $this->getSignedInUserID();
        
            $groupsRep = new GroupRepository();

            $groups = $groupsRep->getUserGroups($user);

            $this->render("tasknNotes", ["userGroups"=>$groups, "activeGroupID"=>$user->getActiveGroupID()]);
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