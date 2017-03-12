<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Tests\Quests\Integration;

class ServiceProviderTest extends AbstractIntegrationTest
{
    public function testGetAllServices()
    {
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
        $this->app->boot();
    }
}
