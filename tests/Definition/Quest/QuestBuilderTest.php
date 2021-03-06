<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Definition\Quest;

use LittleCubicleGames\Quests\Definition\Quest\QuestBuilder;
use LittleCubicleGames\Quests\Definition\Reward\RewardBuilder;
use LittleCubicleGames\Quests\Definition\Task\TaskBuilder;
use LittleCubicleGames\Quests\Definition\Task\TaskInterface;
use PHPUnit\Framework\TestCase;

class QuestBuilderTest extends TestCase
{
    /** @var QuestBuilder */
    private $builder;
    /** @var TaskInterface&\PHPUnit\Framework\MockObject\MockObject */
    private $task;
    /** @var TaskBuilder&\PHPUnit\Framework\MockObject\MockObject */
    private $taskBuilder;
    /** @var RewardBuilder&\PHPUnit\Framework\MockObject\MockObject */
    private $rewardBuilder;

    protected function setUp(): void
    {
        $this->task = $this->getMockBuilder(TaskInterface::class)->getMock();
        $this->taskBuilder = $this->getMockBuilder(TaskBuilder::class)->getMock();
        $this->rewardBuilder = $this->getMockBuilder(RewardBuilder::class)->getMock();
        $this->builder = new QuestBuilder($this->taskBuilder, $this->rewardBuilder);
    }

    public function testBuild(): void
    {
        $data = [
            'id' => 1,
            'task' => $task = [],
        ];

        $this->taskBuilder
            ->expects($this->once())
            ->method('build')
            ->with($this->equalTo($task))
            ->willReturn($this->task);

        $quest = $this->builder->build($data);

        $this->assertSame(1, $quest->getId());
        $this->assertSame($this->task, $quest->getTask());
    }
}
