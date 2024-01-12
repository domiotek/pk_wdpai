<?php 

class Event {
    private int $id;
    private int $initiatorID;
    private int $groupID;
    private string $eventType;
    private string $targetType;
    private int $targetID;
    private DateTime $when;

    public function __construct(int $id, int $initiatorID, int $groupID, string $eventType, int $targetID, string $targetType, DateTime $when) {
        $this->id = $id;
        $this->initiatorID = $initiatorID;
        $this->groupID = $groupID;
        $this->eventType = $eventType;
        $this->targetID = $targetID;
        $this->targetType = $targetType;
        $this->when = $when;
    }
    
    public function getId(): int {
        return $this->id;
    }

    public function getInitiatorID(): int {
        return $this->initiatorID;
    }

    public function getGroupID(): int {
        return $this->groupID;
    }

    public function getEventType(): string {
        return $this->eventType;
    }

    public function getTargetID(): int {
        return $this->targetID;
    }

    public function getTargetType(): string {
        return $this->targetType;
    }

    public function getWhen(): DateTime {
        return $this->when;
    }
}