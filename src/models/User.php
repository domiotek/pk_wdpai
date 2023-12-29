<?php

class User {
    private int $userID;
    private string $email;
    private string $passwordHash;
    private string $name;
    private string $createdAt;
    private int|null $activeGroupID;


    public function __construct(int $userID, string $email, string $password, string $name, string $createdAt, int|null $activeGroupID) {
        $this->userID = $userID;
        $this->email = $email;
        $this->passwordHash = $password;
        $this->name = $name;
        $this->createdAt= $createdAt;
        $this->activeGroupID = $activeGroupID;
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

    public function getActiveGroupID() {
        return $this->activeGroupID;
    }

    public function setActiveGroupID(int|null $activeGroupID) {
        $this->activeGroupID = $activeGroupID;
    }
}

