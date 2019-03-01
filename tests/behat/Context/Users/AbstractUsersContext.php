<?php
declare(strict_types = 1);

namespace Context\Users;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use App\ValueObject\Username;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Context\AppTrait;
use Context\MessageBus;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
class AbstractUsersContext implements KernelAwareContext
{
    use AppTrait;

    /**
     * @var MessageBus
     */
    protected $messageBusContext;

    /**
     * @return UserRepositoryInterface
     */
    public function users()
    {
        return $this->get(UserRepositoryInterface::class);
    }

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();

        $this->messageBusContext = $environment->getContext(MessageBus::class);
    }

    /**
     * @return User
     */
    protected function getByUsernameOrThrowException(string $username): User
    {
        return $this->users()->getByUsernameOrThrowException(new Username($username));
    }
}
