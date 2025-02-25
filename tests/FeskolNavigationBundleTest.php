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

use Feskol\Bundle\NavigationBundle\DependencyInjection\Compiler\NavigationRegisterPass;
use Feskol\Bundle\NavigationBundle\FeskolNavigationBundle;
use Feskol\Bundle\NavigationBundle\Navigation\NavigationRegistry;
use Feskol\Bundle\NavigationBundle\Tests\Fixtures\Navigation\Attribute\FooNavigation;
use Feskol\Bundle\NavigationBundle\Tests\Fixtures\Navigation\Processor\TestNavigationProcessor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\Bundle\Bundle;

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

    private function getContainerConfigurator(ContainerBuilder $containerBuilder, Bundle $bundle): ContainerConfigurator
    {
        $instancesOf = [];

        return new ContainerConfigurator(
            $containerBuilder,
            new PhpFileLoader($containerBuilder, new FileLocator($bundle->getPath().'/config')),
            $instancesOf,
            __DIR__,
            'test.yaml'
        );
    }

    private function getConfig(): array
    {
        return [
            'template' => 'nav-template.html.twig',
            'active_as_link' => true,
        ];
    }

    public function testLoadExtension(): void
    {
        $bundle = new FeskolNavigationBundle();
        $containerBuilder = new ContainerBuilder();
        $containerConfigurator = $this->getContainerConfigurator($containerBuilder, $bundle);

        $bundle->loadExtension($this->getConfig(), $containerConfigurator, $containerBuilder);

        // config files loaded check
        $this->assertTrue($containerBuilder->hasDefinition('feskol_navigation.navigation_registry'));

        $this->assertTrue($containerBuilder->hasDefinition('feskol_navigation.link_service'));
        $this->assertTrue($containerBuilder->hasDefinition('feskol_navigation.navigation_processor_runner'));
        $this->assertTrue($containerBuilder->hasDefinition('feskol_navigation.navigation_processor.default'));

        $this->assertTrue($containerBuilder->hasDefinition('feskol_navigation.twig.extension'));
        $this->assertTrue($containerBuilder->hasDefinition('feskol_navigation.twig.runtime'));
    }

    public function testLoadExtensionAutoTagNavigation(): void
    {
        $bundle = new FeskolNavigationBundle();
        $containerBuilder = new ContainerBuilder();
        $containerConfigurator = $this->getContainerConfigurator($containerBuilder, $bundle);

        $bundle->loadExtension($this->getConfig(), $containerConfigurator, $containerBuilder);

        $containerBuilder->register(FooNavigation::class, FooNavigation::class)
            ->setAutoconfigured(true)
            ->setPublic(true);

        $this->assertNotEmpty($containerBuilder->getDefinition(FooNavigation::class));
        $fooNav = $containerBuilder->getDefinition(FooNavigation::class);
        $this->assertFalse($fooNav->hasTag('feskol_navigation.navigation'));

        $containerBuilder->compile();

        $this->assertNotEmpty($containerBuilder->getDefinition(FooNavigation::class));

        $fooNav = $containerBuilder->getDefinition(FooNavigation::class);
        $this->assertTrue($fooNav->hasTag('feskol_navigation.navigation'));
    }

    /**
     * @dataProvider getClassRegistrationTests
     */
    public function testLoadExtensionInterfaceRegistration(
        string $interfaceClass,
        string $expectedAliasId,
    ): void {
        $bundle = new FeskolNavigationBundle();
        $containerBuilder = new ContainerBuilder();
        $containerConfigurator = $this->getContainerConfigurator($containerBuilder, $bundle);

        $this->assertFalse($containerBuilder->has($interfaceClass));

        $bundle->loadExtension($this->getConfig(), $containerConfigurator, $containerBuilder);

        $this->assertTrue($containerBuilder->has($interfaceClass));
        $this->assertSame($expectedAliasId, (string) $containerBuilder->getAlias($interfaceClass));
    }

    public static function getClassRegistrationTests(): array
    {
        return [
            [NavigationRegistry::class, 'feskol_navigation.navigation_registry'],
        ];
    }

    public function testLoadExtensionAutoConfigureNavigationProcessors(): void
    {
        $bundle = new FeskolNavigationBundle();
        $containerBuilder = new ContainerBuilder();
        $containerConfigurator = $this->getContainerConfigurator($containerBuilder, $bundle);

        $containerBuilder->register(TestNavigationProcessor::class, TestNavigationProcessor::class)
            ->setAutoconfigured(true)
            ->setPublic(true);

        $this->assertFalse($containerBuilder->getDefinition(TestNavigationProcessor::class)->hasTag('feskol_navigation.navigation_processor'));

        $bundle->loadExtension($this->getConfig(), $containerConfigurator, $containerBuilder);

        $containerBuilder->compile();

        $this->assertTrue($containerBuilder->getDefinition(TestNavigationProcessor::class)->hasTag('feskol_navigation.navigation_processor'));

        $tag = $containerBuilder->getDefinition(TestNavigationProcessor::class)->getTag('feskol_navigation.navigation_processor');

        $this->assertNotEmpty($tag);
        $this->assertArrayHasKey('priority', $tag[0]);
    }

    public function testLoadExtensionAutoConfigureNavigationProcessorsAlreadyDefined(): void
    {
        $bundle = new FeskolNavigationBundle();
        $containerBuilder = new ContainerBuilder();
        $containerConfigurator = $this->getContainerConfigurator($containerBuilder, $bundle);

        $containerBuilder->register(TestNavigationProcessor::class, TestNavigationProcessor::class)
            ->setAutoconfigured(true)
            ->setPublic(true)
        ->addTag('feskol_navigation.navigation_processor', ['priority' => 80]);

        $this->assertTrue($containerBuilder->getDefinition(TestNavigationProcessor::class)->hasTag('feskol_navigation.navigation_processor'));
        $tag = $containerBuilder->getDefinition(TestNavigationProcessor::class)->getTag('feskol_navigation.navigation_processor');
        $this->assertNotEmpty($tag);
        $this->assertArrayHasKey('priority', $tag[0]);
        $this->assertEquals(80, $tag[0]['priority']);

        $bundle->loadExtension($this->getConfig(), $containerConfigurator, $containerBuilder);

        $containerBuilder->compile();

        $this->assertTrue($containerBuilder->getDefinition(TestNavigationProcessor::class)->hasTag('feskol_navigation.navigation_processor'));
        $tag = $containerBuilder->getDefinition(TestNavigationProcessor::class)->getTag('feskol_navigation.navigation_processor');
        $this->assertNotEmpty($tag);
        $this->assertArrayHasKey('priority', $tag[0]);
        $this->assertEquals(80, $tag[0]['priority']);
    }

    public function testBuildRegistersCompilerPasses(): void
    {
        $bundle = new FeskolNavigationBundle();
        $containerBuilder = new ContainerBuilder();

        $bundle->build($containerBuilder);
        $passes = $containerBuilder->getCompilerPassConfig()->getPasses();

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
