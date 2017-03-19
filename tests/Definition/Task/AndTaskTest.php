<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Tests\Quests\Definition\Task;

use LittleCubicleGames\Quests\Definition\Task\AndTask;
use LittleCubicleGames\Quests\Definition\Task\TaskInterface;
use PHPUnit\Framework\TestCase;

class AndTaskTest extends TestCase
{
    /**
     * @dataProvider isFinishedProvider
     */
    public function testIsFinished($value, $expected)
    {
        $taskMock = $this->getMockBuilder(TaskInterface::class)->getMock();
        $taskMock->method('isFinished')->willReturn($value);
        $task = new AndTask(array($taskMock));
        $this->assertSame($expected, $task->isFinished(array()));
    }
    public function isFinishedProvider()
    {
        return array(array(false, false), array(true, true));
    }
    public function testIsFinishedMultiple()
    {
        $successTaskMock = $this->getMockBuilder(TaskInterface::class)->getMock();
        $successTaskMock->method('isFinished')->willReturn(true);
        $failTaskMock = $this->getMockBuilder(TaskInterface::class)->getMock();
        $failTaskMock->method('isFinished')->willReturn(false);
        $task = new AndTask(array($successTaskMock, $failTaskMock));
        $this->assertFalse($task->isFinished(array()));
        $task = new AndTask(array($failTaskMock, $successTaskMock));
        $this->assertFalse($task->isFinished(array()));
        $task = new AndTask(array($successTaskMock, $successTaskMock));
        $this->assertTrue($task->isFinished(array()));
    }
    public function testGetTaskIdTypesEmpty()
    {
        $task = new AndTask(array());
        $this->assertSame(array(), $task->getTaskIdTypes());
    }
    public function testGetTaskIdTypes()
    {
        $task1Mock = $this->getMockBuilder(TaskInterface::class)->getMock();
        $task1Mock->method('getTaskIdTypes')->willReturn(array(1 => 'type1'));
        $task2Mock = $this->getMockBuilder(TaskInterface::class)->getMock();
        $task2Mock->method('getTaskIdTypes')->willReturn(array(2 => 'type2'));
        $task = new AndTask(array($task1Mock, $task2Mock));
        $this->assertEquals(array(1 => 'type1', 2 => 'type2'), $task->getTaskIdTypes());
        $task = new AndTask(array($task2Mock, $task1Mock));
        $this->assertEquals(array(2 => 'type2', 1 => 'type1'), $task->getTaskIdTypes());
    }
}
