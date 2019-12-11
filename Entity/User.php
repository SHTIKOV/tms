<?php

namespace Entity;

/**
 * @Entity
 * @Table(name="users")
 * 
 * @author КОнстантин Штыков (SHTIKOV)
 */
class User
{
    /** @Id @Column(type="integer") @GeneratedValue */
    private $id;

    /** 
     * @Column(type="string", nullable=true) 
     * 
     * @var string
     */
    private $name;

    /** 
     * @Column(type="string") 
     * 
     * @var string
     */
    private $email;

    /**
     * @Column(type="datetime")
     * 
     * @var \DateTime
     */
    private $created;


    public function __construct () {
        $this->created = new \DateTime ();
    }


    public function getId (): int {
        return $this->id;
    }

    public function getName (): string {
        return $this->name;
    }

    public function setName (string $name): User {
        $this->name = $name;
        return $this;
    }

    public function getEmail (): string {
        return $this->name;
    }

    public function setEmail (string $email): User {
        $this->email = $email;
        return $this;
    }
}