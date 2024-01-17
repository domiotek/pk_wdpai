<?php

require_once "Repository.php";
require_once __DIR__ . "/../models/User.php";


class UserRepository extends Repository {

    public function createUser(string $email, string $password, string $name) {
        $conn = $this->database->connect();
        $query = $conn->prepare("INSERT INTO users(email, \"password\", \"name\") VALUES(?,?,?);");

        $query->execute([
            $email,
            $password,
            $name
        ]);

        return $this->getUser($email);
    }

    public function getUser(string|int $input): ?User {
        $conn = $this->database->connect();

        $field = "email";

        if(gettype($input)=="integer") {
            $field = "\"userID\"";
        }

        $query = $conn->prepare("SELECT * FROM users WHERE $field=:val;");
        $query->bindParam(":val",$input, PDO::PARAM_STR);
        $query->execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if($user === false) {
            return null;
        }

        return new User($user["userID"],$user["email"], $user["password"], $user["name"], $user["createdAt"], $user["activeGroupID"]);
    }

    public function updateUser(User $user): void {
        $conn = $this->database->connect();

        $query = $conn->prepare("UPDATE users SET email=:email, password=:passwd, name=:name, \"activeGroupID\"=:group WHERE \"userID\"=:id;");

        $ID = $user->getID();
        $email = $user->getEmail();
        $passwd = $user->getPasswordHash();
        $name = $user->getName();
        $group = $user->getActiveGroupID();

        $query->bindParam(":email",$email, PDO::PARAM_STR);
        $query->bindParam(":passwd",$passwd, PDO::PARAM_STR);
        $query->bindParam(":name",$name, PDO::PARAM_STR);
        $query->bindParam(":group",$group, PDO::PARAM_INT);
        $query->bindParam(":id",$ID, PDO::PARAM_INT);
        $query->execute();
    }

}