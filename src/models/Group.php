<?php 

class Group {
    private int $id;
    private string $name;
    private DateTime $creationDate;
    private string $invitationCode;

    public function __construct(int $id, string $name, string $creationDate, string $invitationCode) {
        $this->id = $id;
        $this->name = $name;
        $this->creationDate = new DateTime($creationDate);
        $this->invitationCode = $invitationCode;
    }

    public function getID(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getCreationDate(): DateTime {
        return $this->creationDate;
    }

    public function getInvitationCode(): string {
        return $this->invitationCode;
    }
}