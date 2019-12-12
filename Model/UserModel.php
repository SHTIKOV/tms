<?php

namespace Model;

use League\Route\Http\Exception\UnauthorizedException;
use Doctrine\ORM\EntityManager;
use Entity\User;

class UserModel {

    /** @var EntityManager */
    protected $em;

    public function __construct (EntityManager $em) {
        $this->em = $em;
    }

    public function register (array $data): void {
        if ($this->isExists ($data['email'], $this->getMd5Password ($data['password']))) {
            return;
        }

        $user = (new User ())
            ->setEmail ($data['email'])
            ->setName ($data['name'] ?? 'Unnamed')
            ->setPassword ($this->getMd5Password ($data['password']));
            
        $this->em->persist ($user);
    }

    public function login (string $email, string $password): void {
        $user = $this->em->getRepository (User::class)
            ->findOneBy ([
                'email' => $email, 
                'password' => $this->getMd5Password ($password)
            ]);
            
        if (!($user instanceof User)) {
            throw new UnauthorizedException ('User not found');
        }

        $user->setToken ($this->generateToken ($user));

        $this->em->persist ($user);

        $sessionDeathTime = $this->getSessionDeathTime ();
        setcookie ("id", $user->getId (), $sessionDeathTime);
        setcookie ("token", $user->getToken (), $sessionDeathTime);
    }

    public function logout (): void {
        unset($_COOKIE['id']);
        setcookie('id', null, -1, '/');
        unset($_COOKIE['token']);
        setcookie('token', null, -1, '/');
    }

    public function check (array $cookies): bool {
        if (isset ($cookies['id']) && isset ($cookies['token'])) {
            $user = $this->getUserByToken ($cookies['token']);
            return true;
        }
        return false;
    }

    public function isExists (string $email, string $password): bool {
        $user = $this->em->getRepository (User::class)
            ->findOneBy ([
                'email' => $email, 
                'password' => $password
            ]);
        
        return $user instanceof User;
    }

    public function getUserByToken (?string $token): ?User {
        if (is_null ($token)) {
            return null;
        }

        return $this->em->getRepository (User::class)
            ->findOneBy (['token' => $token]);
    }

    private function getMd5Password (string $password): string {
        return md5 (md5 (trim ($password)));
    }

    private function generateToken (User $user): string {
        return md5 (md5 ($user->getId () . trim ($user->getPassword ())));
    }

    private function getSessionDeathTime (): int {
        return time () + 60 * 60 * 24 * 30;
    }

}