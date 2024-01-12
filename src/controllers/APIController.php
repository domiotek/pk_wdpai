<?php

require_once "AppController.php";

class APIController extends AppController {
    
    function toggleTaskState() {
        header("Content-Type: application/json");

        if(!$this->isAuthenticated()) {
            http_response_code(401);
            echo json_encode(["status"=>"Failure", "message"=>"You need to be signed in."]);
            die();
        }

        if(!isset($_GET["taskID"])) {
            http_response_code(400);
            echo json_encode(["status"=>"Failure", "message"=>"taskID query parameter is required."]);
            die();
        }

        $taskRep = new TaskRepository();
        $task = $taskRep->getTask($_GET["taskID"]);

        if($task) {
            $task->setIsCompleted(!$task->getIsCompleted());
            $taskRep->updateTask($task);

            echo json_encode(["status"=> "Success", "message"=> "Task toggled."]);
        }else {
            http_response_code(404);
            echo json_encode(["status"=> "Failure","message"=> "Couldn't find such task."]);
            die();
        }
    }
}