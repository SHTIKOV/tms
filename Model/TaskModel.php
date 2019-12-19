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
        $isEdit = false;
        if (!isset ($data['id']) || !$data['id']) {
            $task = new Task ();
        } else {
            $task = $this->em->getRepository (Task::class)
                ->find ($data['id']);
            $isEdit = true;
        }

        $task
            ->setEmail ($data['email'])
            ->setUsername ($data['username'])
            ->setDescription ($data['description'])
            ->setStatus ($data['status']);

        if ($isEdit) {
            $task->setIsEdited (true);
        }
            
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