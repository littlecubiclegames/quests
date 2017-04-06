<?php declare(strict_types=1);

namespace Definition\Registry;

use Doctrine\Common\Cache\Cache;
use LittleCubicleGames\Quests\Definition\Quest\Quest;
use LittleCubicleGames\Quests\Definition\Quest\QuestBuilder;
use LittleCubicleGames\Quests\Definition\Registry\RegistryInterface;
use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Guard\TriggerValidator;
use PHPUnit\Framework\TestCase;

abstract class AbstractRegistryTest extends TestCase
{
    protected $questBuilder;
    protected $triggerValidator;
    protected $cache;

    protected function setUp()
    {
        $this->questBuilder = $this->getMockBuilder(QuestBuilder::class)->disableOriginalConstructor()->getMock();
        $this->triggerValidator = $this->getMockBuilder(TriggerValidator::class)->disableOriginalConstructor()->getMock();
        $this->cache = $this->getMockBuilder(Cache::class)->getMock();
    }

    public function testGetNextQuest()
    {
        $userId = 1;
        $questId = 12;
        $questData = ['id' => $questId];
        $slot = $this->getMockBuilder(Slot::class)->disableOriginalConstructor()->getMock();
        $registry = $this->setupRegistry([$questId => $questData]);

        $quest = $this->getMockBuilder(Quest::class)->disableOriginalConstructor()->getMock();
        $this->mockBuildAndCache($questId, $quest, $questData);

        $this->triggerValidator
            ->expects($this->once())
            ->method('canTrigger')
            ->with($this->equalTo($quest), $this->equalTo($slot), $this->equalTo($userId))
            ->willReturn(true);

        $this->assertSame($quest, $registry->getNextQuest($userId, $slot));
    }

    public function testGetNextQuestCannotTrigger()
    {
        $userId = 1;
        $questId = 12;
        $questData = ['id' => $questId];
        $slot = $this->getMockBuilder(Slot::class)->disableOriginalConstructor()->getMock();
        $registry = $this->setupRegistry([$questId => $questData]);

        $quest = $this->getMockBuilder(Quest::class)->disableOriginalConstructor()->getMock();

        $this->mockBuildAndCache($questId, $quest, $questData);

        $this->triggerValidator
            ->expects($this->once())
            ->method('canTrigger')
            ->with($this->equalTo($quest), $this->equalTo($slot), $this->equalTo($userId))
            ->willReturn(false);

        $this->assertNull($registry->getNextQuest($userId, $slot));
    }

    public function testGetNextQuestNoQuest()
    {
        $userId = 1;
        $slot = $this->getMockBuilder(Slot::class)->disableOriginalConstructor()->getMock();
        $registry = $this->setupRegistry([]);

        $this->assertNull($registry->getNextQuest($userId, $slot));
    }

    protected function mockBuildAndCache($questId, Quest $quest, array $questData)
    {
        $this->questBuilder
            ->expects($this->once())
            ->method('build')
            ->with($this->equalTo($questData))
            ->willReturn($quest);

        $this->cache
            ->expects($this->once())
            ->method('contains')
            ->with($this->equalTo($questId))
            ->willReturn(false);

        $this->cache
            ->expects($this->once())
            ->method('save')
            ->with($this->equalTo($questId), $this->equalTo($quest));

        $this->cache
            ->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo($questId))
            ->willReturn($quest);
    }

    abstract protected function setupRegistry(array $quests): RegistryInterface;
}
