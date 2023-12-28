<?php

require_once "Repository.php";
require_once __DIR__ . "/../models/Session.php";

class SessionRepository extends Repository {

    public function getSession(string $token): ?Session {
        $conn = $this->database->connect();

        $query = $conn->prepare("SELECT * FROM sessions NATURAL JOIN users WHERE \"sessionID\"=:id;");
        $query->bindParam(":id",$token, PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if($result === false) {
            return null;
        }

        $user = new User($result["userID"], $result["email"], $result["password"], $result["name"], $result["createdAt"]);

        return new Session($token, $user, $result["validUntil"]);
    }

    public function createSession(User $user): ?Session {
        $conn = $this->database->connect();
        $query = $conn->prepare("INSERT INTO sessions(\"sessionID\", \"userID\", \"validUntil\") VALUES(?,?,?);");

        $newToken = bin2hex(random_bytes(16));

        $expirationTime = new DateTime();
        $expirationTime->add(new DateInterval("P7D"));

        $query->execute([
            $newToken,
            $user->getID(),
            $expirationTime->format($expirationTime::ATOM)
        ]);

        return $this->getSession($newToken);
    }

    public function deleteSession(string $token): void {
        $conn = $this->database->connect();

        $query = $conn->prepare("DELETE FROM sessions WHERE \"sessionID\"=:id;");

        $query->bindParam(":id",$token, PDO::PARAM_STR);

        $query->execute();
    }

    public function touchSession(string $token): void {
        $conn = $this->database->connect();
        $query = $conn->prepare("UPDATE sessions SET \"validUntil\"=:date WHERE \"sessionID\"=:id;");

        $query->bindParam(":id",$token, PDO::PARAM_STR);

        $expirationTime = new DateTime();
        $expirationTime->add(new DateInterval("P7D"));
        $expirationTime = $expirationTime->format($expirationTime::ATOM);
        
        $query->bindParam(":date",$expirationTime);

        $query->execute();

    }

}