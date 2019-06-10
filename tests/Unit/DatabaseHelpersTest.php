<?php

namespace Squadron\Base\Tests\Unit;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Squadron\Base\Helpers\Database\DatabaseSchema;
use Squadron\Base\Tests\TestCase;

class DatabaseHelpersTest extends TestCase
{
    use RefreshDatabase;

    /** @var Builder */
    private $connection;

    public function setUp(): void
    {
        parent::setUp();

        $this->connection = Schema::connection('testbench');
    }

    public function testTablesCreation(): void
    {
        // table #1
        DatabaseSchema::create('table_one', function (Blueprint $table) {
            $table->boolean('active');
        });

        // table #2
        DatabaseSchema::create('table_two', function (Blueprint $table) {
            $table->string('phone');
        }, ['table_one']);

        // table #3 (custom triggers)
        DatabaseSchema::create('table_three', function (Blueprint $table) {
            $table->string('phone');
        }, ['table_one' => ['onUpdate' => 'set null', 'onDelete' => 'set null']]);

        $this->assertTrue($this->connection->hasColumn('table_one', 'uuid'), 'Table #1 created');
        $this->assertTrue($this->connection->hasColumn('table_one', 'active'), 'Table #1 has `active` column');
        $this->assertTrue($this->connection->hasColumn('table_two', 'tableOneUuid'), 'Table #2 created and has reference to #1');
        $this->assertTrue($this->connection->hasColumn('table_two', 'phone'), 'Table #2 has `phone` column');
        $this->assertTrue($this->connection->hasColumn('table_three', 'tableOneUuid'), 'Table #3 created and has reference to #1');
    }

    public function testSystemColumnsCreation(): void
    {
        // table with timestamps
        DatabaseSchema::create('table_timestamps', function (Blueprint $table) {
            $table->boolean('active');
        }, null, true);

        $this->assertTrue($this->connection->hasColumn('table_timestamps', 'createdAt'));
        $this->assertTrue($this->connection->hasColumn('table_timestamps', 'updatedAt'));
        $this->assertFalse($this->connection->hasColumn('table_timestamps', 'sortOrder'));

        // table with sortings
        DatabaseSchema::create('table_sorts', function (Blueprint $table) {
            $table->boolean('active');
        }, null, false, true);

        $this->assertFalse($this->connection->hasColumn('table_sorts', 'createdAt'));
        $this->assertFalse($this->connection->hasColumn('table_sorts', 'updatedAt'));
        $this->assertTrue($this->connection->hasColumn('table_sorts', 'sortOrder'));

        // table with all features
        DatabaseSchema::create('table_all', function (Blueprint $table) {
            $table->boolean('active');
        }, null, true, true);

        $this->assertTrue($this->connection->hasColumn('table_all', 'createdAt'));
        $this->assertTrue($this->connection->hasColumn('table_all', 'updatedAt'));
        $this->assertTrue($this->connection->hasColumn('table_all', 'sortOrder'));
    }

    public function testPivotTablesCreation(): void
    {
        // create base tables
        DatabaseSchema::create('modelOne');
        DatabaseSchema::create('modelTwo');
        DatabaseSchema::create('model_one');
        DatabaseSchema::create('model_two');

        // simple
        DatabaseSchema::createPivot('modelOne', 'modelTwo');

        $this->assertTrue($this->connection->hasColumn('modelOne_modelTwo', 'modelOneUuid'));
        $this->assertTrue($this->connection->hasColumn('modelOne_modelTwo', 'modelTwoUuid'));

        // custom tables
        DatabaseSchema::createPivot('modelOneCustom', 'modelTwoCustom', 'model_one', 'model_two');

        $this->assertTrue($this->connection->hasColumn('modelOneCustom_modelTwoCustom', 'modelOneCustomUuid'));
        $this->assertTrue($this->connection->hasColumn('modelOneCustom_modelTwoCustom', 'modelTwoCustomUuid'));
    }
}
