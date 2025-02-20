<?php

/**
 * This file is part of the NavigationBundle project.
 *
 * (c) Festim Kolgeci <festim.kolgei@pm.me>
 *
 * For complete copyright and license details, please refer
 * to the LICENSE file distributed with this source code.
 */

namespace Feskol\Bundle\NavigationBundle\Tests;

use Feskol\Bundle\NavigationBundle\DependencyInjection\Compiler\AutoTagNavigationPass;
use Feskol\Bundle\NavigationBundle\DependencyInjection\Compiler\NavigationPass;
use Feskol\Bundle\NavigationBundle\DependencyInjection\Compiler\NavigationRegisterPass;
use Feskol\Bundle\NavigationBundle\FeskolNavigationBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class FeskolNavigationBundleTest extends TestCase
{
    public function testConfiguration(): void
    {
        $bundle = new FeskolNavigationBundle();

        $definition = $this->createMock(DefinitionConfigurator::class);
        $definition
            ->expects($this->any())
            ->method('rootNode')
            ->willReturn((new TreeBuilder('feskol_navigation'))->getRootNode());

        $bundle->configure($definition);

        $rootNode = $definition->rootNode();

        $this->assertNotNull($rootNode);
        $this->assertArrayHasKey('template', $rootNode->getChildNodeDefinitions());
        $this->assertArrayHasKey('active_as_link', $rootNode->getChildNodeDefinitions());
    }

    public function testLoadExtension(): void
    {
        $bundle = new FeskolNavigationBundle();
        $containerBuilder = new ContainerBuilder();
        $instancesOf = [];
        $containerConfigurator = new ContainerConfigurator(
            $containerBuilder,
            new PhpFileLoader($containerBuilder, new FileLocator($bundle->getPath().'/config')),
            $instancesOf,
            __DIR__,
            'test.yaml'
        );

        $config = [
            'template' => 'custom_template.html.twig',
            'active_as_link' => true,
        ];

        $bundle->loadExtension($config, $containerConfigurator, $containerBuilder);

        // config files loaded check
        $this->assertTrue($containerBuilder->hasDefinition('feskol_navigation.registry'));
        $this->assertTrue($containerBuilder->hasDefinition('feskol_navigation.twig.extension'));
        $this->assertTrue($containerBuilder->hasDefinition('feskol_navigation.twig.runtime'));

        // parameters check
        $this->assertTrue($containerBuilder->hasParameter('feskol_navigation.template'));
        $this->assertTrue($containerBuilder->hasParameter('feskol_navigation.active_as_link'));

        $this->assertEquals('custom_template.html.twig', $containerBuilder->getParameter('feskol_navigation.template'));
        $this->assertTrue($containerBuilder->getParameter('feskol_navigation.active_as_link'));
    }

    public function testBuildRegistersCompilerPasses(): void
    {
        $bundle = new FeskolNavigationBundle();
        $containerBuilder = new ContainerBuilder();

        $bundle->build($containerBuilder);
        $passes = $containerBuilder->getCompilerPassConfig()->getPasses();

        $this->assertTrue($this->hasCompilerPass($passes, NavigationPass::class));
        $this->assertTrue($this->hasCompilerPass($passes, AutoTagNavigationPass::class));
        $this->assertTrue($this->hasCompilerPass($passes, NavigationRegisterPass::class));
    }

    private function hasCompilerPass(array $passes, string $className): bool
    {
        foreach ($passes as $pass) {
            if ($pass instanceof $className) {
                return true;
            }
        }

        return false;
    }
}
