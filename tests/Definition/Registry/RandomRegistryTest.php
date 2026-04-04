<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Definition\Registry;

use LittleCubicleGames\Quests\Definition\Registry\RandomRegistry;
use LittleCubicleGames\Quests\Definition\Registry\RegistryInterface;

class RandomRegistryTest extends AbstractRegistryTest
{
    /**
     * @param array<mixed> $quests
     */
    protected function setupRegistry(array $quests): RegistryInterface
    {
        return new RandomRegistry($quests, $this->questBuilder, $this->triggerValidator, $this->cache, 'registry');
    }
}
