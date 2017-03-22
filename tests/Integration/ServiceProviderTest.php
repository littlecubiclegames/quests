<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Tests\Quests\Integration;

use LittleCubicleGames\Tests\Quests\Mock\Initialization\MockQuestBuilder;

class ServiceProviderTest extends AbstractIntegrationTest
{
    public function testGetAllServices()
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
    public function testBoot()
    {
        $this->app['cubicle.quests.initializer.questbuilder'] = new MockQuestBuilder();
        $this->app->boot();
    }
}
