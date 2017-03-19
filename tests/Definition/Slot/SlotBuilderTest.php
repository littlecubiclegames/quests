<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Tests\Quests\Definition\Slot;

use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Definition\Slot\SlotBuilder;
use PHPUnit\Framework\TestCase;

class SlotBuilderTest extends TestCase
{
    /**
     * @dataProvider buildProvider
     */
    public function testBuild($start, $end)
    {
        $builder = new SlotBuilder();
        $slot = $builder->build(array('id' => 'id', 'registry' => 'registry', 'start' => $start, 'end' => $end));
        $this->assertInstanceOf(Slot::class, $slot);
        $this->assertSame('id', $slot->getId());
        $this->assertSame('registry', $slot->getRegistryId());
    }
    public function buildProvider()
    {
        return array(array(null, null), array('now', 'now'));
    }
}
