<?php

class Task {
    private int $taskID;
    private int $objectID;
    private int $creatorUserID;
    private DateTime $createdAt;
    private string $title;
    private bool $isCompleted;
    private int|null $assignedUserID;
    private DateTime|null $dueDate;


    public function __construct(int $taskID, int $objectID, int $creatorUserID, DateTime $createdAt, string $title, bool $isCompleted, int|null $assignedUserID, DateTime|null $dueDate) {
        $this->taskID = $taskID;
        $this->objectID = $objectID;
        $this->creatorUserID = $creatorUserID;
        $this->createdAt = $createdAt;
        $this->title = $title;
        $this->isCompleted = $isCompleted;
        $this->assignedUserID = $assignedUserID;
        $this->dueDate = $dueDate;
    }

    public function getTaskID() {
        return $this->taskID;
    }

    public function getObjectID() {
        return $this->objectID;
    }

    public function getCreatorUserID() {
        return $this->creatorUserID;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle(string $newTitle) {
        $this->title = $newTitle;
    }

    public function getIsCompleted() {
        return $this->isCompleted;
    }

    public function setIsCompleted(bool $isCompleted) {
        $this->isCompleted = $isCompleted;
    }

    public function getAssignedUserID() {
        return $this->assignedUserID;
    }

    public function setAssignedUserID(int $newUserID) {
        $this->assignedUserID = $newUserID;
    }

    public function getDueDate() {
        return $this->dueDate;
    }

    public function setDueDate(DateTime $newDueDate) {
        $this->dueDate = $newDueDate;
    }
}