<?php

namespace MAB\JS;

class JSArray extends ArrayOrObject
{
    protected bool $breakLine = false;


    public function __construct(...$args)
    {
        $this->items = $args;
    }

    public function format(): string
    {
        $items = array_map(function ($struct) {
            return $this->formatChildStruct($struct);
        }, $this->items);

        $items = implode($this->getItemSeparator(), $items);

        return $this->createArray($items);
    }

    public function breakLine(): self
    {
        $this->breakLine = true;
        return $this;
    }

    private function createArray($items): string
    {
        if ($items !== null && $items !== '') {
            return $this->createArrayWithItems($items);
        } else {
            return '[]';
        }
    }

    private function createArrayWithItems($items): string
    {
        if ($this->breakLine) {
            $ret = "["
                . $this->breakLineAndIndent()
                . $items
                . "\n"
                . $this->tab(-1)
                . "]";
        } else {
            $ret = "[$items]";
        }

        return $ret;
    }
}