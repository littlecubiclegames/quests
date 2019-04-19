<?php declare(strict_types = 1);

namespace LittleCubicleGames\Tests\Quests\Slot;

use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Definition\Slot\SlotBuilder;
use LittleCubicleGames\Quests\Slot\StaticSlotLoader;
use PHPUnit\Framework\TestCase;

class StaticSlotLoaderTest extends TestCase
{
    public function testGetSlotsForUserEmpty(): void
    {
        $userId = 1;
        $builder = $this->getMockBuilder(SlotBuilder::class)->getMock();
        $builder
            ->expects($this->never())
            ->method('build');
        $loader = new StaticSlotLoader([], $builder);

        $slotCollection = $loader->getSlotsForUser($userId);
        $this->assertSame([], $slotCollection->getUnusedSlots());
    }

    public function testGetSlotsForUser(): void
    {
        $userId = 1;

        $slotId = 'slotId';
        $slotData = ['slot'];

        $slot = $this->getMockBuilder(Slot::class)->disableOriginalConstructor()->getMock();
        $slot
            ->expects($this->any())
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

        $slotCollection = $loader->getSlotsForUser($userId);
        $this->assertSame([$slotId => $slot], $slotCollection->getUnusedSlots());
    }

    public function testGetSlotsForUserInactive(): void
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
        $slotCollection = $loader->getSlotsForUser($userId);

        $this->assertSame([], $slotCollection->getUnusedSlots());
    }
}
