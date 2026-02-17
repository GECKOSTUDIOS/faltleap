<?php

declare(strict_types=1);

namespace FaltLeap;

class LeapSafeData implements \IteratorAggregate, \Countable, \JsonSerializable
{
    private mixed $raw;

    public function __construct(mixed $data)
    {
        $this->raw = $data;
    }

    public function __get(string $name): mixed
    {
        $value = is_object($this->raw) ? ($this->raw->$name ?? null) : ($this->raw[$name] ?? null);

        if (is_string($value)) {
            return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }

        if (is_array($value) || is_object($value)) {
            return new self($value);
        }

        return $value;
    }

    public function __isset(string $name): bool
    {
        if (is_object($this->raw)) {
            return isset($this->raw->$name);
        }

        if (is_array($this->raw)) {
            return isset($this->raw[$name]);
        }

        return false;
    }

    public function __toString(): string
    {
        return htmlspecialchars((string) $this->raw, ENT_QUOTES, 'UTF-8');
    }

    public function getIterator(): \Traversable
    {
        $items = is_array($this->raw) ? $this->raw : (is_iterable($this->raw) ? iterator_to_array($this->raw) : []);

        foreach ($items as $key => $item) {
            if (is_array($item) || is_object($item)) {
                yield $key => new self($item);
            } else {
                yield $key => $item;
            }
        }
    }

    public function count(): int
    {
        if (is_countable($this->raw)) {
            return count($this->raw);
        }

        return 0;
    }

    public function jsonSerialize(): mixed
    {
        return $this->raw;
    }
}
