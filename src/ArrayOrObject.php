<?php

namespace MAB\JS;

use ArrayAccess;
use Countable;

abstract class ArrayOrObject extends Structure implements ArrayAccess, Countable
{
    public array $items;

    protected function getItemSeparator(): string
    {
        return $this->breakLine
            ? (",\n" . $this->tab())
            : ', ';
    }

    protected function breakLineAndIndent(): string
    {
        return "\n" . $this->tab();
    }


    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->items[$offset] ?? null;
    }

    public function count(): int
    {
        return count($this->items);
    }

}