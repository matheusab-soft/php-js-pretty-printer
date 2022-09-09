<?php

namespace MAB\JS;

class Raw
{
    public string $js;

    public function __construct(string $str)
    {
        $this->js = $str;
    }
}