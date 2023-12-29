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

            $this->render("addGroup", ["subtitle"=>"Want to explore something new?", "userGroups"=>$groups, "activeGroupID"=>$user->getActiveGroupID()]);
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

            $this->render("group", ["userGroups"=>$groups, "activeGroupID"=>$user->getActiveGroupID()]);
        }else {
            $this->redirect("login?r=session_expired");
        }
    }
}