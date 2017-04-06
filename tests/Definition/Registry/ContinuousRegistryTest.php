<?php

namespace Definition\Registry;

use LittleCubicleGames\Quests\Definition\Registry\ContinuousRegistry;

class ContinuousRegistryTest extends AbstractRegistryTest
{
    protected function setupRegistry(array $quests)
    {
        return new ContinuousRegistry($quests, $this->questBuilder, $this->triggerValidator, $this->cache, 'registry');
    }
}
