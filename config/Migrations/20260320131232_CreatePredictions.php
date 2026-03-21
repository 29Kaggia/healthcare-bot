<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreatePredictions extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     *
     * @return void
     */
    public function change(): void
{
    $table = $this->table('predictions');
    $table->addColumn('conversation_id', 'integer')
          ->addColumn('disease', 'string', ['limit' => 255])
          ->addColumn('confidence', 'float')
          ->addColumn('advice', 'text')
          ->addColumn('created', 'datetime')
          ->addForeignKey('conversation_id', 'conversations', 'id', ['delete' => 'CASCADE'])
          ->create();
}
}
