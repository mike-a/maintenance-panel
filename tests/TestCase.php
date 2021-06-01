<?php
/**
 * Created by PhpStorm
 * User: jjoek
 * Date: 5/30/21
 * Time: 2:28 pm
 */

namespace Vivinet\EngineersConsole\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Vivinet\Basetheme\BasethemeServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            BasethemeServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {

    }

    /** @test */
    public function test_example()
    {
        $this->assertTrue(true);
    }
}
