<?php

namespace Entity;

/**
 * @Entity
 * @Table(name="tasks")
 * 
 * @author Константин Штыков (SHTIKOV)
 */
class Task implements \JsonSerializable {
    /** @Id @Column(type="integer") @GeneratedValue */
    private $id;

    /** 
     * @Column(type="string", nullable=true) 
     * 
     * @var string
     */
    private $username;

    /** 
     * @Column(type="string") 
     * 
     * @var string
     */
    private $email;

    /** 
     * @Column(type="text") 
     * 
     * @var string
     */
    private $description;

    /** 
     * @Column(type="string") 
     * 
     * @var string
     */
    private $status;

    /** 
     * @Column(type="boolean") 
     * 
     * @var bool
     */
    private $isEdited = false;

    /**
     * @Column(type="datetime")
     * 
     * @var \DateTime
     */
    private $created;


    public function __construct () {
        $this->created = new \DateTime ();
    }


    public function getId (): ?int {
        return $this->id;
    }

    public function getUsername (): string {
        return $this->username;
    }

    public function setUsername (string $username): Task {
        $this->username = $username;
        return $this;
    }

    public function getEmail (): string {
        return $this->name;
    }

    public function setEmail (string $email): Task {
        $this->email = $email;
        return $this;
    }

    public function getDescription (): string {
        return $this->description;
    }

    public function setDescription (string $description): Task {
        $this->description = $description;
        return $this;
    }

    public function getStatus (): string {
        return $this->status;
    }

    public function setStatus (string $status): Task {
        $this->status = $status;
        return $this;
    }

    public function getIsEdited (): bool {
        return $this->isEdited;
    }

    public function setIsEdited (bool $isEdited): Task {
        $this->isEdited = $isEdited;
        return $this;
    }

    public function getCreated (): \DateTime {
        if (!($this->created instanceof \DateTime)) {
            return new \DateTime ($this->created);
        }

        return $this->created;
    }
    
    public function jsonSerialize (): array {
        $output = get_object_vars ($this);
        return $output;
    }
}