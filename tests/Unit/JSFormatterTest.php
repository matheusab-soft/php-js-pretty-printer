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
            <<<js
                const x = 1;
                let y = 2;
                
                function z(a, b) {
                    return [
                        a,
                        b
                    ];
                }
                js,
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

    public function testDefaultIndent()
    {
        $actual = JS::object([
            'a' => [
                'a_a' => [1, 2]
            ]
        ])->format();
        $expected = <<<js
            {
                "a": {
                    "a_a": [1, 2]
                }
            }
            js;

        $this->assertEquals($expected, $actual);


    }
    public function testCustomIndent()
    {
        JS::$INDENT_CONTENT = '  ';
        $actual = JS::object([
            'a' => [
                'a_a' => [1, 2]
            ]
        ])->format();
        $expected = <<<js
            {
              "a": {
                "a_a": [1, 2]
              }
            }
            js;

        $this->assertEquals($expected, $actual);


    }
}