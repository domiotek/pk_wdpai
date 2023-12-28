<?php

class User {
    private int $userID;
    private string $email;
    private string $passwordHash;
    private string $name;
    private string $createdAt;


    public function __construct(int $userID, string $email, string $password, string $name, string $createdAt) {
        $this->userID = $userID;
        $this->email = $email;
        $this->passwordHash = $password;
        $this->name = $name;
        $this->createdAt= $createdAt;
    }

    public function getID() {
        return $this->userID;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail(string $newVal) {
        $this->email = $newVal;
    }

    public function getPasswordHash() {
        return $this->passwordHash;
    }

    public function setPassword(string $newVal) {
        $this->passwordHash = $newVal;
    }
    public function getName() {
        return $this->name;
    }

    public function setName(string $newVal) {
        $this->name = $newVal;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }
}

