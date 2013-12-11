<?php

use Phpmig\Migration\Migration;

class AddIdeasTable extends Migration
{
    protected $tableName;

    /* @var \Illuminate\Database\Schema\Builder $schema */
    protected $schema;

    public function init()
    {
        $this->tableName = 'ideas';
        $this->schema = $this->get('schema');
    }

    /**
     * Do the migration
     */
    public function up()
    {
        /* @var \Illuminate\Database\Schema\Blueprint $table */
        $this->schema->create($this->tableName, function ($table)
        {
            $table->increments('id');
            $table->string('title');
            $table->text('content')->nullable();
            $table->smallInteger('public')->default(1);
            $table->smallInteger('display')->default(1);
            $table->integer('user_id');
            $table->string('ip_address', 20);
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $this->schema->drop($this->tableName);
    }
}
