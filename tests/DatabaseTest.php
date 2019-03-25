<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Schema;
use PDO;
use Tests\TestCase;

class DatabaseTest extends TestCase
{
    /**
     * Test database connection.
     *
     * @return void
     */
    public function testConnection()
    {
        $this->assertNotEmpty($this->getConnection()->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION));
    }

    /**
     * Test database migrations.
     *
     * @depends testConnection
     *
     * @return void
     */
    public function testMigrations()
    {
        $config = config('laravel-permission.table_names');

        // Check base tables.
        $this->assertTrue(Schema::hasTable('migrations'));
        $this->assertTrue(Schema::hasTable('users'));
        $this->assertTrue(Schema::hasTable('password_resets'));

        // Check permission tables.
        $this->assertTrue(Schema::hasTable($config['sessions']));
        $this->assertTrue(Schema::hasTable($config['roles']));
        $this->assertTrue(Schema::hasTable($config['permissions']));
        $this->assertTrue(Schema::hasTable($config['settings']));

        // Check custom tables.
    }
}