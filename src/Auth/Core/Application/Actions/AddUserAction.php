<?php

declare(strict_types=1);

namespace Marketplace\Auth\Core\Application\Actions;

use Marketplace\Auth\Core\Application\Exceptions\UserExistException;
use Marketplace\Auth\Core\Domain\Entities\User;
use Marketplace\Auth\Core\Domain\Events\UserRegistered;
use Marketplace\Auth\Core\Ports\UserMangerContract;
use Marketplace\Auth\Core\Ports\UserRepositoryContract;
use Marketplace\Shared\Core\Ports\DomainEventDispatcherContract;

class AddUserAction
{
    public function __construct(
        private readonly UserRepositoryContract $userRepository,
        private readonly UserMangerContract $userManger,
        private readonly DomainEventDispatcherContract $eventDispatcher,
    ) {}

    /**
     * @throws UserExistException
     */
    public function run(string $email, string $name, string $password): User
    {
        $user = $this->userRepository->getByEmail($email);

        if ($user) {
            throw new UserExistException();
        }

        $user = $this->userManger->createUser($email, $name, $password);

        $this->eventDispatcher->dispatch(new UserRegistered($user->id));

        return $user;
    }
}
