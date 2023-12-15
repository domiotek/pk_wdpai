<?php

class User {
    private $email;
    private $password;
    private $name;
    private $surname;


    public function __construct(string $email,string $password,string $name,string $surname) {
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->surname = $surname;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail(string $newVal) {
        $this->email = $newVal;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword(string $newVal) {
        $this->password = $newVal;
    }
    public function getName() {
        return $this->name;
    }

    public function setName(string $newVal) {
        $this->name = $newVal;
    }

    public function getSurname() {
        return $this->surname;
    }

    public function setSurname(string $newVal) {
        $this->surname = $newVal;
    }

}

