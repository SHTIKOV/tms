<?php

namespace Model;

use Doctrine\ORM\EntityManager;
use Entity\Task;

class TaskModel {

    /** @var EntityManager */
    protected $em;

    public function __construct (EntityManager $em) {
        $this->em = $em;
    }

    public function edit (array $data): Task {
        if (!isset ($data['id']) || !$data['id']) {
            $task = new Task ();
        } else {
            $task = $this->em->getRepository (Task::class)
                ->find ($data['id']);
        }

        if (isset ($data['email'])) {
            $task->setEmail ($data['email']);
        }
        
        if (isset ($data['username'])) {
            $task->setUsername ($data['username']);
        }

        if (isset ($data['status'])) {
            $task->setStatus ($data['status']);
        }

        if ((new UserModel ($this->em))->check ($_COOKIE)) {
            $task->setStatus (true);
        }
            
        $this->em->persist ($task);
    }

    public function get (array $data = []): array {
        $tasks = $this->em->getRepository (Task::class)
            ->findBy ($data);

        return array_map (function ($task) {
            return $task->jsonSerialize ();
        }, $tasks);
    }
}