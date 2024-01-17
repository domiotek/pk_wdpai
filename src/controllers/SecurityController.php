<?php

require_once "AppController.php";
require_once __DIR__ . "/../repository/UserRepository.php";
require_once __DIR__ ."/../repository/SessionRepository.php";

class SecurityController extends AppController {

    public function verifyPassword(string $inputPasswd, string $storedPasswd) {
        $salt = substr($storedPasswd,0,10);
        $hash = substr($storedPasswd,10);

        $sampledHash = hash("sha512", $salt . $inputPasswd);
        return $sampledHash === $hash;
    }

    public function hashPassword(string $password): string {
        $salt = bin2hex(random_bytes(5));
        $hash = hash("sha512", $salt . $password);

        return $salt . $hash;
    }

    public function passwordTest() {

        if(!isset($_REQUEST["password"])) {
            echo "Pass password to hash as 'password' query param.";
            die();
        }

        $hashed = $this->hashPassword($_REQUEST["password"]);

        echo "Input: " . $_REQUEST["password"] . "<br>";
        echo "Salt: " . substr($hashed,0,10) ."<br>";
        echo "Hash: " . substr($hashed,10) ."<br><br>";
        echo "Complete hash: $hashed";
    }

    public function register() {
        if($this->isAuthenticated()) {
            $this->redirect("home");
        }

        if(!$this->isPOST()) {
            return $this->render("register");
        }


        $userRepository = new UserRepository();

        $email = $_POST["username"];
        $password = $_POST["password"];
        $cpassword = $_POST["cpassword"];
        $name = $_POST["name"];
        
        if($password != $cpassword) {
            return $this->render("register", ["message"=>"Passwords don't match.", "email"=>$email, "name"=>$name]);
        }

        $userCheck = $userRepository->getUser($email);
        if($userCheck) {
            return $this->render("register", ["message"=>"User already exists.", "email"=>$email, "name"=>$name]);
        }


        $user = $userRepository->createUser($email, $this->hashPassword($password),$name);

        if($user) {
            $this->redirect("login?r=after_register");
        }else {
            return $this->render("login", ["message"=>"Couldn't register you right now. Try again in a bit.", "email"=>$email, "name"=>$name]);
        }
    }

    public function login() {

        if($this->isAuthenticated()) {
            $this->redirect("home");
        }

        if(!$this->isPOST()) {
            $message = null;

            if(isset($_REQUEST["r"])) {
                if($_REQUEST["r"]=="session_expired") {
                    $message = "Your session expired";
                }else if($_REQUEST["r"]=="after_register") {
                    $message = "Registration completed. You can login now.";
                }
            }
            return $this->render("login", ["message"=>$message]);
        }


        $userRepository = new UserRepository();
        $sessionRepository = new SessionRepository();

        $email = $_POST["username"];
        $password = $_POST["password"];
        $rememberMe =  "off";

        if(isset($_POST["rememberMe"])) {
            $rememberMe = $_POST["rememberMe"];
        }

        $user = $userRepository->getUser($email);

        if($user == null || $email != $user->getEmail() || !$this->verifyPassword($password, $user->getPasswordHash())) {
            return $this->render("login", ["message"=>"Invalid email and / or password."]);
        }

        $session = $sessionRepository->createSession($user);

        if(!$session) {
            return $this->render("login", ["message"=> "Couldn't log you in. Try again in a bit."]);
        }

        $expirationDate = 0;

        if($rememberMe=="on") {
            $expirationDate = strtotime("+7 days");
        }else setcookie("short-lived-session","1");

        setcookie("session",$session->getToken(), $expirationDate);

        $this->redirect("home");
    }

    public function logout() {
        if(!isset($_COOKIE["session"])) {
            $this->redirect("login");
        }

        $sessions = new SessionRepository();

        $session = $sessions->getSession($_COOKIE["session"]);

        if($session) {
            $sessions->deleteSession($session->getToken());
        }

        setcookie("session","");
        $this->redirect("login");
    }
}