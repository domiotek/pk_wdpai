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
        $errMessage = "";
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
            $this->redirect("gropu?r=$errCode");
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
}