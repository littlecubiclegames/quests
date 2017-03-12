<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Tests\Quests\Progress\Functions;

use LittleCubicleGames\Quests\Progress\Functions\HandlerFunctionInterface;
use PHPUnit\Framework\TestCase;

abstract class AbstractFunctionTest extends TestCase
{
    /** @var HandlerFunctionInterface */
    protected $function;
    public function testGetEventMap()
    {
        $map = $this->function->getEventMap();
        $this::assertNotEmpty($map);
        foreach ($map as $method) {
            method_exists($this->function, $method);
        }
    }
}
