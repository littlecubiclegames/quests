<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Integration;

use LittleCubicleGames\Tests\Quests\Mock\Initialization\MockQuestBuilder;

class ServiceProviderTest extends AbstractIntegrationTest
{
    public function testGetAllServices(): void
    {
        $this->app['cubicle.quests.initializer.questbuilder'] = new MockQuestBuilder();
        $keys = $this->app->keys();
        foreach ($keys as $key) {
            if (strpos($key, 'cubicle') === 0) {
                $service = $this->app[$key];

                $this->assertNotNull($service);
            }
        }
    }

    public function testBoot(): void
    {
        $this->app['cubicle.quests.initializer.questbuilder'] = new MockQuestBuilder();
        $this->app->boot();
    }
}
