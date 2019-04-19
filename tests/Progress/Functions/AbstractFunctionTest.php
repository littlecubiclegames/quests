<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Progress\Functions;

use LittleCubicleGames\Quests\Progress\Functions\QuestFunctionInterface;
use PHPUnit\Framework\TestCase;

abstract class AbstractFunctionTest extends TestCase
{
    /** @var QuestFunctionInterface */
    protected $function;

    public function testGetEventMap(): void
    {
        $map = $this->function->getEventMap();

        $this::assertNotEmpty($map);
        foreach ($map as $method) {
            method_exists($this->function, $method);
        }
    }
}
