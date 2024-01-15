<?php

    require_once __DIR__ . "/../models/Note.php";

    class NoteRepository extends Repository {
        function getNotes(Group $group, int $limit = 0) {
            $conn = $this->database->connect();
    
            $query = $conn->prepare("SELECT * FROM notes NATURAL JOIN objects WHERE \"groupID\"=:id " . ($limit>0?"LIMIT $limit;":";"));
    
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

        function getNote(int $noteID) {
            $conn = $this->database->connect();

            $query = $conn->prepare("SELECT * FROM notes NATURAL JOIN objects WHERE \"noteID\"=:id;");
            $query->bindParam(":id",$noteID, PDO::PARAM_INT);
            $query->execute();
    
            $noteData = $query->fetch(PDO::FETCH_ASSOC);
    
            if($noteData === false) {
                return null;
            }
    
            return new Note($noteData["noteID"],$noteData["objectID"], $noteData["creator"], new DateTime($noteData["createdAt"]), $noteData["title"], $noteData["content"]);
        }

        function getNoteByObjectID(int $objectID) {
            $conn = $this->database->connect();

            $query = $conn->prepare("SELECT * FROM notes NATURAL JOIN objects WHERE \"objectID\"=:id;");
            $query->bindParam(":id",$objectID, PDO::PARAM_INT);
            $query->execute();
    
            $noteData = $query->fetch(PDO::FETCH_ASSOC);
    
            if($noteData === false) {
                return null;
            }
    
            return new Note($noteData["noteID"],$noteData["objectID"], $noteData["creator"], new DateTime($noteData["createdAt"]), $noteData["title"], $noteData["content"]);
        }

        function createNote(string $title, Group $targetGroup, User $creator, string $content) {
            $conn = $this->database->connect();

            $conn->beginTransaction();

            $query = $conn->prepare("INSERT INTO objects(\"groupID\", creator) VALUES(?,?);");
    
            $query->execute([
                $targetGroup->getID(),
                $creator->getID(),
            ]);

            if($conn->lastInsertId() === false) {
                $conn->rollBack();
                return null;
            }

            $objectID = $conn->lastInsertId();

            $query = $conn->prepare("INSERT INTO notes(\"objectID\", title, content) VALUES(?,?,?);");

            $query->execute([
                $objectID,
                $title,
                $content
            ]);

            if($conn->lastInsertId() === false) {
                $conn->rollBack();
                return null;
            }

            $conn->commit();
    
            return $this->getNote($conn->lastInsertId());
        }

        function updateNote(Note $note) {
            $conn = $this->database->connect();
            
            $taskID = $note->getNoteID();
            $title = $note->getTitle();
            $state = $note->getContent();

            $query = $conn->prepare("UPDATE notes SET title=:title, content=:content WHERE \"noteID\"=:id;");
            $query->bindParam(":title", $title, PDO::PARAM_STR);
            $query->bindParam(":content", $state, PDO::PARAM_STR);
            $query->bindParam(":id", $taskID, PDO::PARAM_INT);

            $query->execute();
        }

        function deleteNote(Note $note) {
            $conn = $this->database->connect();

            $conn->beginTransaction();
            $taskID = $note->getNoteID();
            $objectID = $note->getObjectID();

            try {
                $query = $conn->prepare("DELETE FROM notes WHERE \"noteID\"=:id;");
                $query->bindParam(":id", $taskID, PDO::PARAM_INT);
        
                $query->execute();

                $query = $conn->prepare("DELETE FROM objects WHERE \"objectID\"=:id");
                $query->bindParam(":id", $objectID, PDO::PARAM_INT);

                $query->execute();

                $conn->commit();

            }catch(PDOException $e) {
                $conn->rollBack();
            }

        }

    }
    

?>