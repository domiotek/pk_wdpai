<?php

require_once "Repository.php";
require_once __DIR__ . "/../models/User.php";


class UserRepository extends Repository {
    public function getUser(string|int $input): ?User {
        $conn = $this->database->connect();

        $field = "email";

        if(gettype($input)=="integer") {
            $field = "userID";
        }

        $query = $conn->prepare("SELECT * FROM users WHERE $field=:val;");
        $query->bindParam(":val",$input, PDO::PARAM_STR);
        $query->execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if($user === false) {
            return null;
        }

        return new User($user["userID"],$user["email"], $user["password"], $user["name"], $user["createdAt"]);
    }

}