<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Integration;

use LittleCubicleGames\Quests\ServiceProvider;
use PHPUnit\Framework\TestCase;
use Silex\Application;

abstract class AbstractIntegrationTest extends TestCase
{
    /** @var Application */
    protected $app;

    protected function setUp(): void
    {
        $this->app = new Application();
        $this->app->register(new ServiceProvider());
    }
}
