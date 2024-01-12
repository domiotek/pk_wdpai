<?php

require_once "Repository.php";
require_once __DIR__ . "/../models/Event.php";

class EventRepository extends Repository {

    public function getAllEvents(Group $group) {
        $conn = $this->database->connect();
    
        $query = $conn->prepare("SELECT * FROM changelog NATURAL JOIN event_types INNER JOIN object_types ON \"targetObjectID\"=\"objectID\" WHERE \"groupID\"=:id ORDER BY \"when\" DESC");

        $groupID = $group->getID();
        $query->bindParam(":id",$groupID, PDO::PARAM_INT);

        $query->execute();

        $events = $query->fetchAll();

        $result = [];

        if(sizeof($events) > 0) {
            foreach($events as $eventData) {
                $targetType = is_null($eventData["taskID"])?"note":"task";
                array_push($result,new Event($eventData["eventID"],$eventData["initiator"], $eventData["groupID"], $eventData["name"],$eventData["targetObjectID"], $targetType, new DateTime($eventData["when"])));
            }
        }

        return $result;
    }

    public function getEvent(int $eventID) {
        $conn = $this->database->connect();

        $query = $conn->prepare("SELECT * FROM changelog NATURAL JOIN event_types INNER JOIN object_types ON \"targetObjectID\"=\"objectID\" WHERE \"eventID\"=:id;");
        $query->bindParam(":id",$eventID, PDO::PARAM_INT);
        $query->execute();

        $eventData = $query->fetch(PDO::FETCH_ASSOC);

        if($eventData === false) {
            return null;
        }

        $targetType = is_null($eventData["taskID"])?"note":"task";
        return new Event($eventData["eventID"],$eventData["initiator"], $eventData["groupID"], $eventData["name"],$eventData["targetObjectID"], $targetType, new DateTime($eventData["when"]));
    }

    public function createEvent(User $initiator, Group $group, string $eventType, Note|Task $object) {
        $conn = $this->database->connect();

        $conn->beginTransaction();

        $query = $conn->prepare("SELECT \"eventTypeID\" FROM event_types WHERE \"name\"=:eventType;");
        $query->bindValue(":eventType", $eventType, PDO::PARAM_STR);
        $query->execute();

        if($query === false) {
            $conn->rollBack();
            return null;
        }

        $eventTypeID = $query->fetch(PDO::FETCH_ASSOC)["eventTypeID"];

        $query = $conn->prepare("INSERT INTO changelog(initiator, \"groupID\", \"eventTypeID\", \"targetObjectID\") VALUES(?,?,?,?);");

        $query->execute([
            $initiator->getID(),
            $group->getID(),
            $eventTypeID,
            $object->getObjectID()
        ]);

        if($conn->lastInsertId() === false) {
            $conn->rollBack();
            return null;
        }

        $conn->commit();

        return $this->getEvent($conn->lastInsertId());
    }

    public function deleteEvent(Event $event): void {
        $conn = $this->database->connect();

        $query = $conn->prepare("DELETE FROM changelog WHERE \"eventID\"=:id;");

        $query->bindValue(":id",$event->getID(), PDO::PARAM_INT);

        $query->execute();
    }

    public function trimEventsCount(array $events) {
        for($i = sizeof($events); $i >= 8; $i--) {
            $event = end($events);
            $this->deleteEvent($event);
        }
    }

}