<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Tests\Quests\Definition\Task;

use LittleCubicleGames\Quests\Definition\Task\MoreThanTask;
use PHPUnit\Framework\TestCase;

class MoreThanTaskTest extends TestCase
{
    /**
     * @dataProvider isFinishedProvider
     */
    public function testIsFinished($value, $taskId, array $progressMap, $expected)
    {
        $task = new MoreThanTask($taskId, 'type', $value);
        $this->assertSame($expected, $task->isFinished($progressMap));
    }
    public function isFinishedProvider()
    {
        return [[0, 0, [0 => 1], true], [0, 0, [0 => 0], false], [0, 0, [0 => -1], false], [0, 1, [0 => 99, 1 => 0], false]];
    }
    public function testGetTaskIdTypes()
    {
        $task = new MoreThanTask(1, 'type', 10);
        $this->assertEquals([1 => 'type'], $task->getTaskIdTypes());
    }
}
