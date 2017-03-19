<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
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
        $builder->expects($this->never())->method('build');
        $loader = new StaticSlotLoader(array(), $builder);
        $slotCollection = $loader->getSlotsForUser($userId);
        $this->assertSame(array(), $slotCollection->getUnusedSlots());
    }
    public function testGetSlotsForUser()
    {
        $userId = 1;
        $slotId = 'slotId';
        $slotData = array('slot');
        $slot = $this->getMockBuilder(Slot::class)->disableOriginalConstructor()->getMock();
        $slot->expects($this->any())->method('isActive')->willReturn(true);
        $slot->expects($this->once())->method('getId')->willReturn($slotId);
        $builder = $this->getMockBuilder(SlotBuilder::class)->getMock();
        $builder->expects($this->once())->method('build')->with($this->equalTo($slotData))->willReturn($slot);
        $loader = new StaticSlotLoader(array($slotData), $builder);
        $slotCollection = $loader->getSlotsForUser($userId);
        $this->assertSame(array($slotId => $slot), $slotCollection->getUnusedSlots());
    }
    public function testGetSlotsForUserInactive()
    {
        $userId = 1;
        $slotData = array('slot');
        $slot = $this->getMockBuilder(Slot::class)->disableOriginalConstructor()->getMock();
        $slot->expects($this->once())->method('isActive')->willReturn(false);
        $builder = $this->getMockBuilder(SlotBuilder::class)->getMock();
        $builder->expects($this->once())->method('build')->with($this->equalTo($slotData))->willReturn($slot);
        $loader = new StaticSlotLoader(array($slotData), $builder);
        $slotCollection = $loader->getSlotsForUser($userId);
        $this->assertSame(array(), $slotCollection->getUnusedSlots());
    }
}
