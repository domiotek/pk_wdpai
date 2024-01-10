<?php
    
    require_once __DIR__ . "/../models/Task.php";

    class TaskRepository extends Repository {
        
        function getAllTasks(Group $group) {
            $conn = $this->database->connect();
    
            $query = $conn->prepare("SELECT * FROM tasks NATURAL JOIN objects WHERE \"groupID\"=:id");
    
            $groupID = $group->getID();
            $query->bindParam(":id",$groupID, PDO::PARAM_INT);
    
            $query->execute();
    
            $notes = $query->fetchAll();
    
            $result = [];
    
            if(sizeof($notes) > 0) {
                foreach($notes as $taskData) {
                    array_push($result,new Task($taskData["taskID"],$taskData["objectID"], $taskData["creator"], new DateTime($taskData["createdAt"]), $taskData["title"], $taskData["finishState"], $taskData["assignedUser"], is_null($taskData["dueDate"])?null:new DateTime($taskData["dueDate"])));
                }
            }
    
            return $result;
        }
    }

?>