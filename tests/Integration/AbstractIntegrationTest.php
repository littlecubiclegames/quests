<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Tests\Quests\Integration;

use LittleCubicleGames\Quests\ServiceProvider;
use PHPUnit\Framework\TestCase;
use Silex\Application;

abstract class AbstractIntegrationTest extends TestCase
{
    /** @var Application */
    protected $app;
    protected function setUp()
    {
        $this->app = new Application();
        $this->app->register(new ServiceProvider());
    }
}
