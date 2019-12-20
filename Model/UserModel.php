<?php

declare (strict_types=1);

namespace Model;

use League\Route\Http\Exception\{
    UnauthorizedException,
    BadRequestException
};
use Doctrine\ORM\EntityManager;
use Entity\User;

/**
 * User model
 * 
 * @author Константин Штыков (SHTIKOV)
 */
class UserModel {

    /** @var EntityManager */
    protected $em;

    public function __construct (EntityManager $em) {
        $this->em = $em;
    }

    /**
     * Register user
     *
     * @param array $data
     * @return void
     * @throws BadRequestException
     */
    public function register (array $data): void {
        if (!isset ($data['email'], $data['username'], $data['password'])) {
            throw new BadRequestException ('Fields "Email", "Username" and "Password" are reqired.');
        }

        if ($this->isExists ($data['email'])) {
            throw new BadRequestException ('User already exist');
        }

        $user = (new User ())
            ->setEmail ($data['email'])
            ->setName ($data['username'])
            ->setPassword ($this->getMd5Password ($data['password']));
            
        $this->em->persist ($user);
    }

    /**
     * Login
     *
     * @param string $email
     * @param string $password
     * @return void
     * @throws BadRequestException
     */
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
        setcookie ("token", $user->getToken (), $sessionDeathTime);
    }

    public function logout (): void {
        unset($_COOKIE['token']);
        setcookie('token', '', -1, '/');
    }

    public function isExists (string $email, ?string $password = null): bool {
        $fields = ['email' => $email];
        if (!is_null ($password)) {
            $fields['password'] = $password;
        }
        $user = $this->em->getRepository (User::class)
            ->findOneBy ($fields);
        
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