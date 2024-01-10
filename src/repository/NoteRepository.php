<?php

    require_once __DIR__ . "/../models/Note.php";

    class NoteRepository extends Repository {
        function getAllNotes(Group $group) {
            $conn = $this->database->connect();
    
            $query = $conn->prepare("SELECT * FROM notes NATURAL JOIN objects WHERE \"groupID\"=:id");
    
            $groupID = $group->getID();
            $query->bindParam(":id",$groupID, PDO::PARAM_INT);
    
            $query->execute();
    
            $notes = $query->fetchAll();
    
            $result = [];
    
            if(sizeof($notes) > 0) {
                foreach($notes as $noteData) {
                    array_push($result,new Note($noteData["noteID"],$noteData["objectID"], $noteData["creator"], new DateTime($noteData["createdAt"]), $noteData["title"], $noteData["content"]));
                }
            }
    
            return $result;
        }

    }
    

?>