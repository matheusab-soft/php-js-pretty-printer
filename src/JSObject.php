<?php

namespace MAB\JS;

class JSObject extends ArrayOrObject
{
    protected bool $breakLine = true;

    public function __construct(?array $items = [])
    {
        $this->items = $items;
    }

    public function format(): string
    {
        $items = [];
        foreach ($this->items as $k => $v) {
            $formattedValue = $this->formatChildStruct($v);
            $k = json_encode($k);
            $items[] = "$k: $formattedValue";
        }

        $args = implode($this->getItemSeparator(), $items);

        return $this->createObject($args);
    }

    private function createObject(string $args)
    {
        if ($args) {
            $ret = "{";
            $ret .= $this->breakLineAndIndent();
            $ret .= $args;
            $ret .= "\n" . $this->closeBracket();
        } else {
            $ret = '{}';
        }
        return $ret;
    }

    private function closeBracket()
    {
        $ret = $this->tab(-1);
        $ret .= '}';
        return $ret;
    }
}