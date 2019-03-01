<?php
declare(strict_types = 1);

namespace Context;

use App\Message\MessageInterface;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Behat\Hook\Call\BeforeScenario;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class MessageBus implements KernelAwareContext
{
    use AppTrait;

    /**
     * @var array array of strings
     */
    private $messageArgs;

    /**
     * @var MessageInterface
     */
    private $message;

    /**
     * @BeforeScenario
     */
    public function clearData()
    {
        $this->message     = null;
        $this->messageArgs = [];
    }

    /**
     * @When I fill in :key with :value
     */
    public function iFillInWith($key, $value): void
    {
        $this->messageArgs[$key] = $value;
    }

    /**
     * @When I send :messageType message
     */
    public function iSendMessage($messageType): void
    {
        $messageClass = "App\Message\\" . $messageType . 'Message';
        if (!class_exists($messageClass)) {
            throw new \RuntimeException(sprintf('Message with "%s" class does not exist', $messageClass));
        }

        //@TODO replace unpack with reflection
        $message = new $messageClass(...array_values($this->messageArgs));
        $this->message = $message;

        try {
            $this->dispatch($this->message);
        } catch (\Exception $e) {
        }
    }

    /**
     * @return MessageInterface
     */
    public function getMessage(): MessageInterface
    {
        return $this->message;
    }
}
