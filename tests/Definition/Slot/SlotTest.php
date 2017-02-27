<?php declare(strict_types = 1);

namespace LittleCubicleGames\Tests\Quests\Definition\Slot;

use LittleCubicleGames\Quests\Definition\Slot\Slot;
use PHPUnit\Framework\TestCase;

class SlotTest extends TestCase
{
    /**
     * @dataProvider isActiveProvider
     */
    public function testIsActive(?\DateTime $start, ?\DateTime $end, bool $expected)
    {
        $slot = new Slot('1', $start, $end);
        $this->assertSame($expected, $slot->isActive());
    }

    public function isActiveProvider()
    {
        return [
            [null, null, true],
            [(new \DateTime())->add(new \DateInterval('P1D')), null, false],
            [null, (new \DateTime())->sub(new \DateInterval('P1D')), false],
            [(new \DateTime())->sub(new \DateInterval('P1D')), null, true],
            [null, (new \DateTime())->add(new \DateInterval('P1D')), true],
            [(new \DateTime())->sub(new \DateInterval('P1D')), (new \DateTime())->add(new \DateInterval('P1D')), true],
        ];
    }
}
