<?php

declare (strict_types=1);

namespace Entity;

/**
 * @Entity
 * @Table(name="users")
 * 
 * @author Константин Штыков (SHTIKOV)
 */
class User implements \JsonSerializable {
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
     * @Column(type="string") 
     * 
     * @var string
     */
    private $password;

    /** 
     * @Column(type="string", nullable=true) 
     * 
     * @var string
     */
    private $token;

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

    public function getName (): string {
        return $this->name;
    }

    public function setName (string $name): User {
        $this->name = $name;
        return $this;
    }

    public function getPassword (): string {
		return $this->password;
	}

	public function setPassword (string $password): User {
		$this->password = $password;
		return $this;
	}

    public function getEmail (): string {
        return $this->email;
    }

    public function setEmail (string $email): User {
        $this->email = $email;
        return $this;
    }

	public function getToken (): ?string {
		return $this->token;
	}

	public function setToken (?string $token): User {
		$this->token = $token;
		return $this;
    }

    public function getCreated (): \DateTime {
        if (!($this->created instanceof \DateTime)) {
            return new \DateTime ($this->created);
        }

        return $this->created;
    }
    
    public function jsonSerialize (): array {
        return get_object_vars ($this);
    }
}