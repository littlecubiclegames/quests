<?php

namespace LittleCubicleGames\Tests\Quests\Integration;

use LittleCubicleGames\Quests\ServiceProvider;
use PHPUnit\Framework\TestCase;
use Silex\Application;

class ServiceProviderTest extends TestCase
{
    /** @var Application */
    private $app;

    protected function setUp()
    {
        $this->app = new Application();
        $this->app->register(new ServiceProvider());
    }

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
