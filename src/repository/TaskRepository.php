<?php
    
    require_once __DIR__ . "/../models/Task.php";

    class TaskRepository extends Repository {
        
        function getAllTasks(Group $group) {
            $conn = $this->database->connect();
    
            $query = $conn->prepare("SELECT * FROM tasks NATURAL JOIN objects WHERE \"groupID\"=:id ORDER BY title");
    
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

        function getTask(int $taskID) {
            $conn = $this->database->connect();

            $query = $conn->prepare("SELECT * FROM tasks NATURAL JOIN objects WHERE \"taskID\"=:id;");
            $query->bindParam(":id",$taskID, PDO::PARAM_INT);
            $query->execute();
    
            $taskData = $query->fetch(PDO::FETCH_ASSOC);
    
            if($taskData === false) {
                return null;
            }
    
            return new Task($taskData["taskID"],$taskData["objectID"], $taskData["creator"], new DateTime($taskData["createdAt"]), $taskData["title"], $taskData["finishState"], $taskData["assignedUser"], is_null($taskData["dueDate"])?null:new DateTime($taskData["dueDate"]));
        }

        function getTaskByObjectID(int $objectID) {
            $conn = $this->database->connect();

            $query = $conn->prepare("SELECT * FROM tasks NATURAL JOIN objects WHERE \"objectID\"=:id;");
            $query->bindParam(":id",$objectID, PDO::PARAM_INT);
            $query->execute();
    
            $taskData = $query->fetch(PDO::FETCH_ASSOC);
    
            if($taskData === false) {
                return null;
            }
    
            return new Task($taskData["taskID"],$taskData["objectID"], $taskData["creator"], new DateTime($taskData["createdAt"]), $taskData["title"], $taskData["finishState"], $taskData["assignedUser"], is_null($taskData["dueDate"])?null:new DateTime($taskData["dueDate"]));
        }

        function createTask(string $title, Group $targetGroup, User $creator, User|null $assignedUser, DateTime|null $dueDate) {
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

            $query = $conn->prepare("INSERT INTO tasks(\"objectID\", title, \"finishState\", \"assignedUser\", \"dueDate\") VALUES(?,?,?,?,?);");

            $query->execute([
                $objectID,
                $title,
                0,
                is_null($assignedUser)?null:$assignedUser->getID(),
                is_null($dueDate)?null:$dueDate->format("Y-m-d H:i:s")
            ]);

            if($conn->lastInsertId() === false) {
                $conn->rollBack();
                return null;
            }

            $conn->commit();
    
            return $this->getTask($conn->lastInsertId());
        }

        function updateTask(Task $task) {
            $conn = $this->database->connect();
            
            $taskID = $task->getTaskID();
            $title = $task->getTitle();
            $state = $task->getIsCompleted()?1:0;
            $user = $task->getAssignedUserID();
            $date = is_null($task->getDueDate())?null:$task->getDueDate()->format("Y-m-d H:i:s");

            $query = $conn->prepare("UPDATE tasks SET title=:title, \"finishState\"=:state, \"assignedUser\"=:user, \"dueDate\"=:date WHERE \"taskID\"=:id;");
            $query->bindParam(":title", $title, PDO::PARAM_STR);
            $query->bindParam(":state", $state, PDO::PARAM_INT);
            $query->bindParam(":user", $user, PDO::PARAM_INT);
            $query->bindParam(":date", $date, PDO::PARAM_STR);
            $query->bindParam(":id", $taskID, PDO::PARAM_INT);

            $query->execute();
        }

        function deleteTask(Task $task) {
            $conn = $this->database->connect();

            $conn->beginTransaction();
            $taskID = $task->getTaskID();
            $objectID = $task->getObjectID();

            try {
                $query = $conn->prepare("DELETE FROM tasks WHERE \"taskID\"=:id;");
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