<?php declare(strict_types=1);

namespace Definition\Registry;

use LittleCubicleGames\Quests\Definition\Registry\ContinuousRegistry;
use LittleCubicleGames\Quests\Definition\Registry\RegistryInterface;

class ContinuousRegistryTest extends AbstractRegistryTest
{
    protected function setupRegistry(array $quests): RegistryInterface
    {
        return new ContinuousRegistry($quests, $this->questBuilder, $this->triggerValidator, $this->cache, 'registry');
    }
}
