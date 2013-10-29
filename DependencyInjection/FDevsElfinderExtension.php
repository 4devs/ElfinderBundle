<?php

namespace FDevs\ElfinderBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class FDevsElfinderExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('f_devs_elfinder.editor', $config['editor']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $connector = $container->getDefinition('f_devs_elfinder.connector');
        foreach ($config['connector'] as $key => $driver) {
            $defDriver = $container->getDefinition($driver['driver']);
            if ($defDriver->isAbstract()) {
                $nameClass = sprintf('f_devs_elfinder.driver.%s.class', $key);
                $driver['class'] = $container->hasParameter($driver['class']) ? $container->getParameter($driver['class']) : $driver['class'];
                $container->setParameter($nameClass, $defDriver->getClass() ? : $driver['class']);
                $defDriver = $container->setDefinition(sprintf('f_devs_elfinder.driver.%s', $key), new DefinitionDecorator($driver['driver']))
                    ->setClass('%' . $nameClass . '%')
                    ->setArguments($defDriver->getArguments());
            }
            $defDriver->addMethodCall('setDriverId', array($key));
            if (count($driver['disabled'])) {
                $defDriver->addMethodCall('setDisabledCmd', array($driver['disabled']));
            }
            if (count($driver['options'])) {
                $defDriver->addMethodCall('addOptions', array($driver['options']));
            }
            if (count($driver['driver_options'])) {
                $defDriver->addMethodCall('setDriverOptions', array($driver['driver_options']));
            }
            $connector->addMethodCall('addDriver', array($defDriver));
        }
    }
}
