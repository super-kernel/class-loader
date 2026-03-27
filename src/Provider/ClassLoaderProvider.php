<?php
declare(strict_types=1);

namespace SuperKernel\ClassLoader\Provider;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use SuperKernel\Attribute\Factory;
use SuperKernel\Attribute\Provider;
use SuperKernel\ClassLoader\ClassLoader;
use SuperKernel\Contract\ClassAutoloaderInterface;

#[
	Provider(ClassAutoloaderInterface::class),
	Factory,
]
final class ClassLoaderProvider
{
	private static ClassAutoloaderInterface $classLoader;

	/**
	 * @param ContainerInterface $container
	 *
	 * @return ClassAutoloaderInterface
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function __invoke(ContainerInterface $container): ClassAutoloaderInterface
	{
		if (!isset(self::$classLoader)) {
			self::$classLoader = $container->get(ClassLoader::class);
			self::$classLoader->register();
		}
		return self::$classLoader;
	}
}