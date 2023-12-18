<?php

require_once 'AppController.php';

class DefaultController extends AppController {

    public function index() {
        $this->render('login');
    }

    public function freshStart() {
        $this->render("freshStart");
    }

    public function dashboard() {
        $this->render("dashboard");
    }

    public function d() {
        $this->render("tasknNotes");
    }

    public function group() {
        $this->render(("group"));
    }
}