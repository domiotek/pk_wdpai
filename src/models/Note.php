<?php

class Note {
    private int $noteID;
    private int $objectID;
    private int $creatorUserID;
    private DateTime $createdAt;
    private string $title;
    private string $content;


    public __construct(int $noteID, int $objectID, int $creatorUserID, DateTime $createdAt, string $title, string $content) {
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

    public getContent() {
        return $this->content;
    }

    public setContent(string $newContent) {
        $this->content = $newContent;
    }
}