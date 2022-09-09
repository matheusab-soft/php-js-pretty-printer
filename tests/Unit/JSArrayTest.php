<?php

namespace Tests\Unit;

use MAB\JS\JS;

class JSArrayTest extends \PHPUnit\Framework\TestCase
{
    public function testArray()
    {
        $actual = JS::array(1, 2, 'yo')->format();
        $expected = '[1, 2, "yo"]';
        $this->assertEquals($expected, $actual);
    }

    public function testEmptyArray()
    {
        $this->assertEquals('[]', JS::array()->format());
    }

    public function testArrayWithZero()
    {
        $this->assertEquals('[0]', JS::array(0)->format());
    }

    public function testEmptyArray_w_lineBreak()
    {
        $this->assertEquals(JS::array()->breakLine()->format(), '[]');
    }

    public function testEmptyObject()
    {
        $this->assertEquals(JS::object()->format(), '{}');
    }

    public function testArrayAccess()
    {
        $array = JS::array();
        $array[] = 1;
        $array[] = 2;
        $array[] = 'yo';

        $expected = '[1, 2, "yo"]';
        $this->assertEquals($expected, $array->format());
    }

    public function testObjectWithArray()
    {
        $actual = JS::object(
            [
                'a' => JS::array(1, 2, 3)->breakLine(),
                'b' => JS::array(1, 2, 3)->breakLine()
            ]
        )->format();

        $expected = <<<JSON
        {
            "a": [
                1,
                2,
                3
            ],
            "b": [
                1,
                2,
                3
            ]
        }
        JSON;
        $this->assertEquals($expected, $actual);
    }

    public function testBreakingArray()
    {
        $actual = JS::array(1, 2, 'yo')
            ->breakLine()
            ->format();

        $expected = <<<str
        [
            1,
            2,
            "yo"
        ]
        str;
        $this->assertEquals($expected, $actual);
    }

    public function testArrayWithPureJS()
    {
        $actual = JS::array(1, JS::raw('function(){return 1;}'))->format();
        $expected = '[1, function(){return 1;}]';
        $this->assertEquals($expected, $actual);
    }

    public function testNestedArray()
    {
        $actual = JS::array(1, 2, JS::array(3, 4))->format();
        $expected = '[1, 2, [3, 4]]';
        $this->assertEquals($expected, $actual);
    }

    public function testObject()
    {
        $actual = JS::object(
            [
                'a' => 1,
                'b' => [1, 'b']
            ]
        )->format();

        $expected = <<<JSON
        {
            "a": 1,
            "b": [1, "b"]
        }
        JSON;

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider nestedObject
     */
    public function testNestedObject($jsObject)
    {
        $actual = $jsObject->format();

        $expected = <<<JSON
        {
            "a": 1,
            "b": {
                "b1": 1,
                "b2": 2
            }
        }
        JSON;

        $this->assertEquals($expected, $actual);
    }

    public function nestedObject()
    {
        return [
            [
                JS::object(
                    [
                        'a' => 1,
                        'b' => JS::object(
                            [
                                'b1' => 1,
                                'b2' => 2
                            ]
                        )
                    ]
                )
            ], [
                JS::object(
                    [
                        'a' => 1,
                        'b' => [
                            'b1' => 1,
                            'b2' => 2
                        ]
                    ]
                )
            ]

        ];
    }

    public function testComplexObject()
    {
        $actual = JS::object(
            [
                'a' => 1,
                'b' => [
                    'b1' => [3, 4],
                    'b2' => [
                        'b2a' => 5,
                        'b2b' => JS::raw(
                            <<<js
                            function() {
                                return 1;
                            }
                            js
                        ),
                        'b3b' => 7
                    ]
                ]
            ]
        )->format();

        $expected = <<<JSON
        {
            "a": 1,
            "b": {
                "b1": [3, 4],
                "b2": {
                    "b2a": 5,
                    "b2b": function() {
                        return 1;
                    },
                    "b3b": 7
                }
            }
        }
        JSON;

        $this->assertEquals($expected, $actual);
    }

}