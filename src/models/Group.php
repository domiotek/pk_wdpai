<?php 

class Group {
    private int $id;
    private string $name;
    private DateTime $creationDate;
    private string $invitationCode;
    private int|null $ownerUserID;

    public function __construct(int $id, string $name, string $creationDate, string $invitationCode, int|null $ownerUserID) {
        $this->id = $id;
        $this->name = $name;
        $this->creationDate = new DateTime($creationDate);
        $this->invitationCode = $invitationCode;
        $this->ownerUserID = $ownerUserID;
    }

    public function getID(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $newName) {
        $this->name = $newName;
    }

    public function getCreationDate(): DateTime {
        return $this->creationDate;
    }

    public function getInvitationCode(): string {
        return $this->invitationCode;
    }

    public function getOwnerUserID(): int {
        return $this->ownerUserID;
    }

    public function setOwnerUserID(int $newOwnerUserID) {
        $this->ownerUserID = $newOwnerUserID;
    }
}