<?php

require_once "AppController.php";
require_once __DIR__ . "/../models/User.php";

class SecurityController extends AppController{

    public function login() {
        $user = new User("test@pk.edu.pl", "admin", "Test", "Account");


        if(!$this->isPOST()) {
            return $this->render("login");
        }

        $email = $_POST["username"];
        $password = $_POST["password"];

        if($email != $user->getEmail() || $password != $user->getPassword()) {
            return $this->render("login", ["message"=>"Invalid email and / or password."]);
        }

        return $this->render("freshStart");
    }
}