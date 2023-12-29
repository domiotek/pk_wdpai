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
            $groups = $groupRepository->getUserGroups($user);
            $message = sizeof($userGroups) >= 3?"You already own maximum of 3 groups.":"Invalid group name.";

            $this->render("addGroup",["createErrMessage"=>$message, "userGroups"=>$groups, "activeGroupID"=>$user->getActiveGroupID()]);
            return;
        }

        $group = $groupRepository->createGroup($name);
        $groupRepository->addGroupMember($group, $user);

        $groupRepository->makeMemberGroupOwner($group, $user);

        $user->setActiveGroupID($group->getID());

        $userRepository->updateUser($user);

        $this->redirect("home");
    }

    public function joinGroup() {
        if(!$this->isAuthenticated() || !$this->isPost()) {
            $this->redirect("new");
        }

        $code = $_POST["code"];
        $user = $this->getSignedInUserID();
        $groupRepository = new GroupRepository();
        $userRepository = new UserRepository();

        $group = $groupRepository->getGroupByInvite($code);

        if($group==null || $groupRepository->isGroupMember($user, $group)) {
            $groups = $groupRepository->getUserGroups($user);
            $message = $group==null?"Invalid invitation code.":"You are already a member of that group.";

            $this->render("addGroup",["joinErrMessage"=>$message, "userGroups"=>$groups, "activeGroupID"=> $user->getActiveGroupID()]);
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
        if(!$this->isAuthenticated()) {
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
}