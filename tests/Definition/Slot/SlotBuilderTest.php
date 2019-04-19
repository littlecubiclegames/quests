<?php declare(strict_types = 1);

namespace LittleCubicleGames\Tests\Quests\Definition\Slot;

use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Definition\Slot\SlotBuilder;
use PHPUnit\Framework\TestCase;

class SlotBuilderTest extends TestCase
{
    /**
     * @dataProvider buildProvider
     */
    public function testBuild(?string $start, ?string $end): void
    {
        $builder = new SlotBuilder();
        $slot = $builder->build([
            'id' => 'id',
            'registry' => 'registry',
            'start' => $start,
            'end' => $end,
        ]);

        $this->assertInstanceOf(Slot::class, $slot);
        $this->assertSame('id', $slot->getId());
        $this->assertSame('registry', $slot->getRegistryId());
    }

    public function buildProvider(): array
    {
        return [
            [null, null],
            ['now', 'now'],
        ];
    }
}
