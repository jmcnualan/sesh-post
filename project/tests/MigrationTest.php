<?php

namespace Tests;

use Tests\TestCase;

class MigrationTest extends TestCase
{
    /**
     * @test
     * @testdox It can migrate and rollback
     *
     * @return void
     */
    public function migrate(): void
    {
        $this->artisan('migrate');
        $this->artisan('migrate:rollback');
        $this->artisan('db:seed');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @testdox Some shit
     *
     * @return void
     */
    public function function(): void
    {
        $this->assertTrue(false);
    }
}
