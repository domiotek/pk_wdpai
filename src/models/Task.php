<?php

class Note {
    private int $noteID;
    private int $objectID;
    private int $creatorUserID;
    private DateTime $createdAt;
    private string $title;
    private bool $isCompleted;
    private int $assignedUserID;
    private DateTime $dueDate;


    public __construct(int $noteID, int $objectID, int $creatorUserID, DateTime $createdAt, string $title, bool $isCompleted, int $assignedUserID, DateTime $dueDate) {
        $this->noteID = $noteID;
        $this->objectID = $objectID;
        $this->creatorUserID = $creatorUserID;
        $this->createdAt = $createdAt;
        $this->title = $title;
    }

    public getNoteID() {
        return $this->noteID;
    }

    public getObjectID() {
        return $this->objectID;
    }

    public getCreatorUserID() {
        return $this->creatorUserID;
    }

    public getCreatedAt() {
        return $this->createdAt;
    }

    public getTitle() {
        return $this->title;
    }

    public setTitle(string $newTitle) {
        $this->title = $newTitle;
    }

    public getIsCompleted() {
        return $this->isCompleted;
    }

    public setIsCompleted(bool $isCompleted) {
        $this->isCompleted = $isCompleted;
    }

    public getAssignedUserID() {
        return $this->assignedUserID;
    }

    public setAssignedUserID(int $newUserID) {
        $this->assignedUserID = $newUserID;
    }

    public getDueDate() {
        return $this->dueDate;
    }

    public setDueDate(DateTime $newDueDate) {
        $this->dueDate = $newDueDate;
    }
}