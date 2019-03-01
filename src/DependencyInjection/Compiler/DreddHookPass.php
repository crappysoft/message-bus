<?php
declare(strict_types = 1);

namespace App\DependencyInjection\Compiler;

use Dredd\HookCollection;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class DreddHookPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(HookCollection::class)) {
            return;
        }

        $definition = $container->findDefinition(HookCollection::class);

        // find all service IDs with the app.mail_transport tag
        $taggedServices = $container->findTaggedServiceIds('dredd.hook');
        $hooks = array_map(function (string $id) {
            return new Reference($id);
        }, array_keys($taggedServices));

        $definition->setArguments([$hooks]);
    }
}
