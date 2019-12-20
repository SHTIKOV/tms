<?php

declare (strict_types=1);

namespace Model;

use Doctrine\ORM\EntityManager;
use Entity\Task;
use Entity\User;
use League\Route\Http\Exception\{
    UnauthorizedException,
    BadRequestException
};

/**
 * Task model
 * 
 * @author Константин Штыков (SHTIKOV)
 */
class TaskModel {

    /** @var EntityManager */
    protected $em;

    public function __construct (EntityManager $em) {
        $this->em = $em;
    }

    /**
     * Edit and create task
     *
     * @param array $data
     * @param User|null $user
     * @return Task
     * @throws UnauthorizedException
     */
    public function edit (array $data, ?User $user = null): Task {
        if (!isset ($data['id']) || !$data['id']) {
            $task = new Task ();
        } else {
            $task = $this->em->getRepository (Task::class)
                ->find ($data['id']);
        }

        if (!is_null ($task->getId ()) && is_null ($user)) {
            throw new UnauthorizedException ('User not found for editing.');
        }

        $task
            ->setEmail ($data['email'])
            ->setUsername ($data['username'])
            ->setStatus ($data['status']);

        /** Set isEdited when user aurhed and edited desciption */
        if (!is_null ($task->getId ()) && $data['description'] !== $task->getDescription ()) {
            $task->setIsEdited (true);
        }

        $task->setDescription ($data['description']);
            
        $this->em->persist ($task);
        return $task;
    }

    /**
     * Remove task
     *
     * @param int $id
     * @return void
     * @throws BadRequestException
     */
    public function remove (int $id): void {
        $task = $this->em->getRepository (\Entity\Task::class)
            ->find ($id);

        if (is_null ($task)) {
            throw new BadRequestException ('Task not found');
        }

        $this->em->remove ($task);
    }

    public function load (array $data = []): \Doctrine\ORM\QueryBuilder {
        $qb = $this->em->createQueryBuilder ();
        $qb
            ->select ('task')
            ->from (Task::class, 'task');

        /** Sorting */
        $sortBy = isset ($data['sortBy']) ? $data['sortBy'] : null;
        if (!is_null ($sortBy) 
            && isset ($sortBy['field'], $sortBy['type']) 
            && !empty ($sortBy['field']) 
            && !empty ($sortBy['type'])) {
            $qb->orderBy ($sortBy['field'], $sortBy['type']);
        }

        /** Limit */
        if (isset ($data['perPage'], $data['currentPage'])) {
            $start = $data['perPage'] * ($data['currentPage'] - 1);
            $end = $start + $data['perPage'];
        }
        
        $qb
            ->setFirstResult ($start ?? 0)
            ->setMaxResults ($end ?? 3);
            
        return $qb;
    }
}