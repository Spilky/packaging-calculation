<?php declare(strict_types = 1);

namespace App\DataStructure;

use App\DataStructure\Exception\ItemNotFoundByIdentity;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use function array_key_exists;
use function array_map;
use function array_values;
use function call_user_func;
use function count;

/**
 * @template T
 * @template-covariant T
 * @implements IteratorAggregate<int, T>
 */
abstract class BaseCollection implements IteratorAggregate, Countable
{

	/**
	 * @var list<T>
	 */
	protected array $items;

	/**
	 * @var array<int, T>|null
	 */
	private array|null $map = null;

	/**
	 * @param array<T> $items
	 */
	final public function __construct(array $items)
	{
		$this->items = array_values($items);
	}

	/**
	 * @return ArrayIterator<int, T>
	 */
	public function getIterator(): ArrayIterator
	{
		return new ArrayIterator($this->items);
	}

	public function count(): int
	{
		return count($this->items);
	}

	public function isEmpty(): bool
	{
		return $this->count() === 0;
	}

	/**
	 * @return list<T>
	 */
	public function toArray(): array
	{
		return $this->items;
	}

	/**
     * @template U
	 * @param callable(T): U $callback
     * @return list<U>
	 */
	public function map(callable $callback): array
	{
		return array_map($callback, $this->items);
	}

    /**
     * @throws ItemNotFoundByIdentity
     * @return T
     */
    public function getByIdentity(int $identity): mixed
    {
        return $this->getMapByIdentity()[$identity] ?? throw new ItemNotFoundByIdentity($identity);
    }

    /**
     * @return callable(T): int
     */
    abstract protected function getIdentityFunction(): callable;

	/**
	 * @return array<int, T>
	 */
	protected function getMapByIdentity(): array
	{
        if ($this->map !== null) {
            return $this->map;
        }

        $this->map = [];

        foreach ($this->items as $item) {
            $key = call_user_func($this->getIdentityFunction(), $item);
            if ( !array_key_exists($key, $this->map)) {
                $this->map[$key] = $item;
            }
        }

        return $this->map;
	}

}
