<?php

require_once "User.php";

class Session {
    private string $token;
    private User $user;
    private DateTime $validUntil;

    public function __construct(string $token, User $user, string $validUntil) {
        $this->token = $token;
        $this->user = $user;
        $this->validUntil = new DateTime($validUntil);
    }

    public function getToken() {
        return $this->token;
    }

    public function getUser() {
        return $this->user;
    }

    public function getExpirationDate() {
        return $this->validUntil;
    }

    public function isValid() {
        $now = new DateTime();

        return $now <= $this->validUntil;
    }
}