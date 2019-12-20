<?php

namespace Model;

use Doctrine\ORM\EntityManager;
use Entity\Task;
use Entity\User;
use League\Route\Http\Exception\UnauthorizedException;

class TaskModel {

    /** @var EntityManager */
    protected $em;

    public function __construct (EntityManager $em) {
        $this->em = $em;
    }

    public function edit (array $data, ?User $user = null): Task {
        if (!isset ($data['id']) || !$data['id']) {
            $task = new Task ();
        } else {
            $task = $this->em->getRepository (Task::class)
                ->find ($data['id']);
        }

        if (!is_null ($task->getId ()) && is_null ($user)) {
            throw new UnauthorizedException ('User not found');
        }

        $task
            ->setEmail ($data['email'])
            ->setUsername ($data['username'])
            ->setStatus ($data['status']);

        if (!is_null ($task->getId ()) && $data['description'] !== $task->getDescription ()) {
            $task->setIsEdited (true);
        }
        $task->setDescription ($data['description']);
            
        $this->em->persist ($task);
        return $task;
    }

    public function remove (int $id): void {
        $task = $this->em->getRepository (\Entity\Task::class)
            ->find ($id);
        $this->em->remove ($task);
    }

    public function get (array $data = []): array {
        $tasks = $this->em->getRepository (Task::class)
            ->findBy ($data);

        return array_map (function ($task) {
            return $task->jsonSerialize ();
        }, $tasks);
    }

    public function load (array $data = []): \Doctrine\ORM\QueryBuilder {
        $start = $data['perPage'] * ($data['currentPage'] - 1);

        $qb = $this->em->createQueryBuilder ();
        $qb
            ->select ('task')
            ->from (Task::class, 'task')
            ->orderBy ("task.id", "DESC");

        if (isset ($data['sortBy']) && $data['sortBy']['field'] !== '') {
            $qb->orderBy ($data['sortBy']['field'], $data['sortBy']['type']);
        }

        $qb
            ->setFirstResult ($start)
            ->setMaxResults ($start + $data['perPage']);

        return $qb;
    }
}