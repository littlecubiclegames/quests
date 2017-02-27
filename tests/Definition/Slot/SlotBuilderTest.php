<?php declare(strict_types = 1);

namespace Definition\Slot;

use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Definition\Slot\SlotBuilder;
use PHPUnit\Framework\TestCase;

class SlotBuilderTest extends TestCase
{
    /**
     * @dataProvider buildProvider
     */
    public function testBuild(?string $start, ?string $end)
    {
        $builder = new SlotBuilder();
        $slot = $builder->build([
            'id' => 'id',
            'start' => $start,
            'end' => $end,
        ]);

        $this->assertInstanceOf(Slot::class, $slot);
    }

    public function buildProvider()
    {
        return [
            [null, null],
            ['now', 'now'],
        ];
    }
}
