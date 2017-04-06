<?php

namespace LittleCubicleGames\Tests\Quests\Definition\Registry;

use Definition\Registry\AbstractRegistryTest;
use LittleCubicleGames\Quests\Definition\Registry\RandomRegistry;

class RandomRegistryTest extends AbstractRegistryTest
{
    protected function setupRegistry(array $quests)
    {
        return new RandomRegistry($quests, $this->questBuilder, $this->triggerValidator, $this->cache, 'registry');
    }
}
