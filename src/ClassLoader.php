<?php
declare(strict_types=1);

namespace SuperKernel\ClassLoader;

use RuntimeException;
use SuperKernel\Contract\ClassLoaderInterface;
use function spl_autoload_register;
use function spl_autoload_unregister;

final readonly class ClassLoader implements ClassLoaderInterface
{
	public function __construct(private array $classMap)
	{
	}

	public function getClassMap(): array
	{
		return $this->classMap;
	}

	public function register(bool $prepend = false): void
	{
		if (!spl_autoload_register([$this, '__autoload'], prepend: $prepend)) {
			throw new RuntimeException('Failed to register ClassAutoloader to the top of the SPL stack.');
		}
	}

	public function unregister(): void
	{
		spl_autoload_unregister([$this, '__autoload']);
	}

	/**
	 * Resolves the class name to its corresponding file path using the internal map.
	 *
	 * This method provides the primary resolution logic for the SPL autoloader mechanism.
	 * It ensures an O(1) lookup and avoids redundant filesystem I/O.
	 *
	 * @param string $class The fully qualified class name.
	 *
	 * @return void
	 * @internal This method is for SPL callback use only.
	 */
	private function __autoload(string $class): void
	{
		if (isset($this->classMap[$class])) {
			include $this->classMap[$class];
		}
	}
}