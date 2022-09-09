<?php

namespace Tests\Unit;

use MAB\JS\JS;
use PHPUnit\Framework\TestCase;

class JSFormatterTest extends TestCase
{

    /**
     * @dataProvider dp
     */
    public function test($lines, $expected)
    {
        $actual = JS::format($lines);
        $this->assertEquals($expected, $actual);
    }

    public function dp()
    {
        return [
            $this->dp1(),
            $this->dp2()
        ];
    }

    private function dp1()
    {
        return [
            [
                'const x = 1;',
                'let y = 2;',
                '',
                'function z(a, b) {',
                [
                    'return [',
                    [
                        'a,',
                        'b'
                    ],
                    '];',
                ],
                '}',
            ],
            <<<str
        const x = 1;
        let y = 2;
        
        function z(a, b) {
            return [
                a,
                b
            ];
        }
        str,
        ];
    }

    private function dp2()
    {
        return [
            [
                'something;',
                [
                    <<<STR
                    this is a
                    multiline
                    string
                    STR
                ]
            ],
            <<<STR
            something;
                this is a
                multiline
                string
            
            STR
        ];
    }

}