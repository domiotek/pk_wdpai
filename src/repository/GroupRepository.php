<?php

require_once "Repository.php";
require_once __DIR__ . "/../models/Group.php";

class GroupRepository extends Repository {

    private function getMemberID(int $userID, Group $group): ?int {
        $conn = $this->database->connect();

        $query = $conn->prepare("SELECT \"memberID\" FROM group_members WHERE \"groupID\"=:id AND \"userID\"=:user;");

        $groupID = $group->getID();
        $query->bindParam(":id",$groupID, PDO::PARAM_INT);
        $query->bindParam(":user", $userID, PDO::PARAM_INT);

        $query->execute();

        $memberID = $query->fetch(PDO::FETCH_ASSOC);

        if($memberID===false) 
            return null;

        return $memberID["memberID"];
    }

    private function resolveMemberID(int|null $memberID) {

        if(is_null($memberID)) return null;
        
        $conn = $this->database->connect();

        $query = $conn->prepare("SELECT \"userID\" FROM group_members WHERE \"memberID\"=:member;");

        $query->bindParam(":member", $memberID, PDO::PARAM_INT);

        $query->execute();

        $memberID = $query->fetch(PDO::FETCH_ASSOC);

        if($memberID===false) 
            return null;

        return $memberID["userID"];
    }

    public function getGroup(int $ID): ?Group {
        $conn = $this->database->connect();

        $query = $conn->prepare("SELECT * FROM groups WHERE \"groupID\"=:id;");
        $query->bindParam(":id",$ID, PDO::PARAM_INT);
        $query->execute();

        $groupData = $query->fetch(PDO::FETCH_ASSOC);

        if($groupData === false) {
            return null;
        }

        return new Group($groupData["groupID"],$groupData["name"], $groupData["createdAt"], $groupData["inviteCode"], $this->resolveMemberID($groupData["ownerMemberID"]));
    }

    public function updateGroup(Group $group) {
        $conn = $this->database->connect();

        $memberID = $this->getMemberID($group->getOwnerUserID(), $group);
        $groupID = $group->getID();
        $name = $group->getName();
        $inviteCode = $group->getInvitationCode();

        $query = $conn->prepare("UPDATE groups SET name=:name, \"inviteCode\"=:code, \"ownerMemberID\"=:owner WHERE \"groupID\"=:group;");
        $query->bindParam(":owner", $memberID, PDO::PARAM_INT);
        $query->bindParam(":name", $name, PDO::PARAM_STR);
        $query->bindParam(":group", $groupID, PDO::PARAM_INT);
        $query->bindParam(":code", $inviteCode, PDO::PARAM_STR);

        $query->execute();
    }

    public function deleteGroup(Group $group) {
        $conn = $this->database->connect();

        $groupID = $group->getID();

        $query = $conn->prepare("DELETE FROM groups WHERE \"groupID\"=:group;");
        $query->bindParam(":group", $groupID, PDO::PARAM_INT);

        $query->execute();
    }

    public function getGroupByInvite(string $code): ?Group {
        $conn = $this->database->connect();

        $query = $conn->prepare("SELECT * FROM groups WHERE \"inviteCode\"=:code;");
        $query->bindParam(":code",$code, PDO::PARAM_STR);
        $query->execute();

        $groupData = $query->fetch(PDO::FETCH_ASSOC);

        if($groupData === false) {
            return null;
        }

        return new Group($groupData["groupID"],$groupData["name"], $groupData["createdAt"], $groupData["inviteCode"], $this->resolveMemberID($groupData["ownerMemberID"]));
    }

    public function getUserGroups(User $user): array {
        $conn = $this->database->connect();

        $query = $conn->prepare("SELECT * FROM groups NATURAL JOIN group_members WHERE \"userID\"=:id;");
        $userID = $user->getID();

        $query->bindParam(":id",$userID, PDO::PARAM_STR);
        $query->execute();

        $groups = $query->fetchAll();

        $result = [];

        if(sizeof($groups) > 0) {
            foreach($groups as $groupData) {
                array_push($result,new Group($groupData["groupID"],$groupData["name"], $groupData["createdAt"], $groupData["inviteCode"], $this->resolveMemberID($groupData["ownerMemberID"])));
            }
        }

        return $result;
    }

    public function getUserOwnedGroups(User $user): array {
        $conn = $this->database->connect();

        $query = $conn->prepare("SELECT * FROM groups NATURAL JOIN group_members WHERE \"userID\"=:id AND \"ownerMemberID\"=\"memberID\";");
        $userID = $user->getID();

        $query->bindParam(":id",$userID, PDO::PARAM_STR);
        $query->execute();

        $groups = $query->fetchAll();

        $result = [];

        if(sizeof($groups) > 0) {
            foreach($groups as $groupData) {
                array_push($result,new Group($groupData["groupID"],$groupData["name"], $groupData["createdAt"], $groupData["inviteCode"], $userID));
            }
        }

        return $result;
    }

    public function createGroup(string $name): ?Group {
        $conn = $this->database->connect();
        $query = $conn->prepare("INSERT INTO groups(name, \"inviteCode\") VALUES(?,?);");

        $inviteCode = bin2hex(random_bytes(3));

        $query->execute([
            $name,
            $inviteCode,
        ]);

        return $this->getGroup($conn->lastInsertId());
    }

    public function getGroupMembers(Group $group): array {
        $conn = $this->database->connect();

        $query = $conn->prepare("SELECT * FROM users NATURAL JOIN group_members WHERE \"groupID\"=:id");
        $groupID = $group->getID();

        $query->bindParam(":id",$groupID, PDO::PARAM_INT);
        $query->execute();

        $users = $query->fetchAll();

        $members = [];

        foreach($users as $userData) {
            array_push($members, new User($userData["userID"],$userData["email"],$userData["password"],$userData["name"], $userData["createdAt"],$userData["activeGroupID"]));
        }

        return $members;
    }

    public function isGroupMember(User $user, Group $group): bool {
        $memberID = $this->getMemberID($user->getID(), $group);
        return $memberID !== null;
    }

    public function addGroupMember(Group $group, User $user) {
        $conn = $this->database->connect();

        $query = $conn->prepare("INSERT INTO group_members(\"groupID\", \"userID\") VALUES(?,?);");

        $query->execute([
            $group->getID(),
            $user->getID()
        ]);
    }

    public function removeGroupMember(Group $group, User $user) {
        $conn = $this->database->connect();

        $query = $conn->prepare("DELETE FROM group_members WHERE \"groupID\"=:group AND \"userID\"=:user;");
        $groupID = $group->getID();
        $userID = $user->getID();

        $query->bindParam(":group",$groupID, PDO::PARAM_INT);
        $query->bindParam(":user",$userID, PDO::PARAM_INT);

        $query->execute();
    }

}