<?php

namespace MAB\JS;

class JS
{
    public static $INDENT_CONTENT = '    ';

    public static function format(array $lines, int $level = 0): string
    {
        $str = '';
        $i = 0;
        foreach ($lines as $v) {
            $i++;
            $isLast = $i == count($lines);

            if (is_array($v)) {
                $str .= self::format($v, $level + 1);
            } else {
                if (strpos($v, "\n") !== false) {
                    $str .= self::format(explode("\n", $v), $level);
                } else {
                    $str .= str_repeat(self::$INDENT_CONTENT, $level) . $v;

                    if (!$isLast || $level > 0) {
                        $str .= "\n";
                    }
                }
            }
        }
        return $str;
    }

    /**
     * @param ...$items
     * @return JSArray
     */
    public static function array(...$items): JSArray
    {
        return new JSArray(...$items);
    }

    public static function object(?array $object = []): JSObject
    {
        return new JSObject($object);
    }

    public static function raw(string $js): Raw
    {
        return new Raw($js);
    }

    public static function arrayFromArray(array $items): JSArray
    {
        return new JSArray(...$items);
    }
}