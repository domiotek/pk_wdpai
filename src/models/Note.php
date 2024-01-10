<?php

class Note {
    private int $noteID;
    private int $objectID;
    private int $creatorUserID;
    private DateTime $createdAt;
    private string $title;
    private string $content;


    public function __construct(int $noteID, int $objectID, int $creatorUserID, DateTime $createdAt, string $title, string $content) {
        $this->noteID = $noteID;
        $this->objectID = $objectID;
        $this->creatorUserID = $creatorUserID;
        $this->createdAt = $createdAt;
        $this->title = $title;
        $this->content = $content;
    }

    public function getNoteID() {
        return $this->noteID;
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

    public function getContent() {
        return $this->content;
    }

    public function setContent(string $newContent) {
        $this->content = $newContent;
    }
}