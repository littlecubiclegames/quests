<?php declare(strict_types = 1);

namespace LittleCubicleGames\Tests\Quests;

use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\QuestAdvancer;
use LittleCubicleGames\Quests\Storage\QuestStorageInterface;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Workflow\Workflow;

class QuestAdvancerTest extends TestCase
{
    /** @var QuestAdvancer */
    private $advancer;
    /** @var QuestStorageInterface&\PHPUnit\Framework\MockObject\MockObject */
    private $storage;
    /** @var Workflow&\PHPUnit\Framework\MockObject\MockObject */
    private $workflow;

    protected function setUp(): void
    {
        $this->storage = $this->getMockBuilder(QuestStorageInterface::class)->getMock();
        $this->workflow = $this->getMockBuilder(Workflow::class)->disableOriginalConstructor()->getMock();
        $this->advancer = new QuestAdvancer(
            $this->storage,
            $this->workflow
        );
    }

    public function testStartQuest(): void
    {
        $questId = 1;
        $userId = 2;
        $transitionName = QuestDefinitionInterface::TRANSITION_START;

        $this->mockSetup($questId, $userId, $transitionName);

        $this->advancer->startQuest($questId, $userId);
    }

    public function testCollectRewardQuest(): void
    {
        $questId = 1;
        $userId = 2;
        $transitionName = QuestDefinitionInterface::TRANSITION_COLLECT_REWARD;

        $this->mockSetup($questId, $userId, $transitionName);

        $this->advancer->collectRewardQuest($questId, $userId);
    }

    public function testAbortQuest(): void
    {
        $questId = 1;
        $userId = 2;
        $transitionName = QuestDefinitionInterface::TRANSITION_ABORT;

        $this->mockSetup($questId, $userId, $transitionName);

        $this->advancer->abortQuest($questId, $userId);
    }

    public function testRejectQuest(): void
    {
        $questId = 1;
        $userId = 2;
        $transitionName = QuestDefinitionInterface::TRANSITION_REJECT;

        $this->mockSetup($questId, $userId, $transitionName);

        $this->advancer->rejectQuest($questId, $userId);
    }

    public function testAdvanceQuest(): void
    {
        $questId = 1;
        $userId = 2;
        $transitionName = 'transition';

        $this->mockSetup($questId, $userId, $transitionName);

        $this->advancer->advanceQuest($questId, $userId, $transitionName);
    }

    private function mockSetup(int $questId, int $userId, string $transitionName): void
    {
        $quest = $this->getMockBuilder(QuestInterface::class)->getMock();
        $this->storage
            ->expects($this->once())
            ->method('getUserQuest')
            ->with($this->equalTo($userId), $this->equalTo($questId))
            ->willReturn($quest);

        $this->workflow
            ->expects($this->once())
            ->method('apply')
            ->with($this->equalTo($quest), $this->equalTo($transitionName));

        $this->storage
            ->expects($this->once())
            ->method('save')
            ->with($this->equalTo($quest));
    }
}
