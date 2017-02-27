<?php declare(strict_types = 1);

namespace LittleCubicleGames\Tests\Quests\Slot;

use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Definition\Slot\SlotBuilder;
use LittleCubicleGames\Quests\Slot\StaticSlotLoader;
use PHPUnit\Framework\TestCase;

class StaticSlotLoaderTest extends TestCase
{
    public function testGetSlotsForUserEmpty()
    {
        $userId = 1;
        $builder = $this->getMockBuilder(SlotBuilder::class)->getMock();
        $builder
            ->expects($this->never())
            ->method('build');
        $loader = new StaticSlotLoader([], $builder);

        $this->assertSame([], $loader->getSlotsForUser($userId));
    }

    public function testGetSlotsForUser()
    {
        $userId = 1;

        $slotId = 'slotId';
        $slotData = ['slot'];

        $slot = $this->getMockBuilder(Slot::class)->disableOriginalConstructor()->getMock();
        $slot
            ->expects($this->once())
            ->method('isActive')
            ->willReturn(true);

        $slot
            ->expects($this->once())
            ->method('getId')
            ->willReturn($slotId);

        $builder = $this->getMockBuilder(SlotBuilder::class)->getMock();
        $builder
            ->expects($this->once())
            ->method('build')
            ->with($this->equalTo($slotData))
            ->willReturn($slot);

        $loader = new StaticSlotLoader([$slotData], $builder);

        $this->assertSame([$slotId => $slot], $loader->getSlotsForUser($userId));
    }

    public function testGetSlotsForUserInactive()
    {
        $userId = 1;

        $slotData = ['slot'];

        $slot = $this->getMockBuilder(Slot::class)->disableOriginalConstructor()->getMock();
        $slot
            ->expects($this->once())
            ->method('isActive')
            ->willReturn(false);

        $builder = $this->getMockBuilder(SlotBuilder::class)->getMock();
        $builder
            ->expects($this->once())
            ->method('build')
            ->with($this->equalTo($slotData))
            ->willReturn($slot);

        $loader = new StaticSlotLoader([$slotData], $builder);

        $this->assertSame([], $loader->getSlotsForUser($userId));
    }
}
