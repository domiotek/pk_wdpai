<?php

require_once 'AppController.php';

class DefaultController extends AppController {

    public function index() {
        $this->redirect("dashboard");
    }

    public function freshStart() {

        if($this->isAuthenticated()) {
            $this->render("freshStart");
        }else {
            $this->redirect("login?r=session_expired");
        }
    }

    public function dashboard() {

        if($this->isAuthenticated()) {
            $this->render("dashboard");
        }else {
            $this->redirect("login?r=session_expired");
        }
    }

    public function d() {
        $this->render("tasknNotes");
    }

    public function group() {
        $this->render(("group"));
    }
}