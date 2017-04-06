<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Definition\Registry;

use Definition\Registry\AbstractRegistryTest;
use LittleCubicleGames\Quests\Definition\Registry\RandomRegistry;
use LittleCubicleGames\Quests\Definition\Registry\RegistryInterface;

class RandomRegistryTest extends AbstractRegistryTest
{
    protected function setupRegistry(array $quests): RegistryInterface
    {
        return new RandomRegistry($quests, $this->questBuilder, $this->triggerValidator, $this->cache, 'registry');
    }
}
