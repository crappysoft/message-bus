<?php
declare(strict_types = 1);

namespace App\ParamConverter;

use App\Message\MessageInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Security\ExpressionLanguage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class MessageConverter implements ParamConverterInterface
{
    private const FORMAT_JSON = 'json';

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ExpressionLanguage
     */
    private $language;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param SerializerInterface $serializer
     * @param ExpressionLanguage  $language
     */
    public function __construct(SerializerInterface $serializer, ExpressionLanguage $language, TokenStorageInterface $tokenStorage)
    {
        $this->serializer = $serializer;
        $this->language = $language;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $class = $configuration->getClass();
        $name  = $configuration->getName();

        $options = $configuration->getOptions();
        $token = $this->tokenStorage->getToken();

        $defaultConstructorArgs = array_map(function ($value) use ($token) {
            return $this->language->evaluate($value, [
                'user'  => $token !== null ? $token->getUser() : null,
                'token' => $token,
            ]);
        }, $options);


        $json    = $request->getContent();
        $message = $this->serializer->deserialize($json, $class, self::FORMAT_JSON, [
            'default_constructor_arguments' => [$class => $defaultConstructorArgs],
        ]);

        $request->attributes->set($name, $message);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration)
    {
        return is_subclass_of($configuration->getClass(), MessageInterface::class, true);
    }
}
