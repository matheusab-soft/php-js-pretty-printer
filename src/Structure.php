<?php

namespace MAB\JS;

abstract class Structure
{
    /** @var int */
    protected $indentationLevel = 1;

    public abstract function format(): string;

    public function indent(int $level): self
    {
        $this->indentationLevel = $level;
        return $this;
    }


    protected function formatChildStruct($struct): string
    {
        if (is_object($struct)) {
            switch (get_class($struct)) {
                case Raw::class:
                    return $this->formatJS($struct);
                case JSObject::class:
                    return $this->formatChildObject($struct);
                case JSArray::class:
                    return $this->formatArray($struct);
            }
        } else {
            if (is_array($struct)) {
                if ($this->isAssociative($struct)) {
                    return $this->formatChildObject(JS::object($struct));
                } else {
                    return $this->formatArray(JS::array(...$struct));
                }
            }
        }
        return json_encode($struct);
    }

    private function formatJS($struct)
    {
        $lines = explode("\n", $struct->js);
        return implode("\n" . $this->tab(), $lines);
    }

    private function formatArray(JSArray $struct): string
    {
        $this->incrementChildIndentationLevel($struct);
        return $struct->format();
    }

    private function formatChildObject(JSObject $struct): string
    {
        $this->incrementChildIndentationLevel($struct);
        return $struct->format();
    }

    private function incrementChildIndentationLevel($struct)
    {
        $struct->indent($this->indentationLevel + 1);
    }

    /**
     * Determines if an array is associative.
     * @param array $array
     * @return bool
     */
    private function isAssociative(array $array): bool
    {
        $keys = array_keys($array);

        return array_keys($keys) !== $keys;
    }

    protected function tab(int $increment = 0): string
    {
        return str_repeat('    ', $this->indentationLevel + $increment);
    }

    public function __toString()
    {
        return $this->format();
    }

}