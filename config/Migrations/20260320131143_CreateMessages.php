<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateMessages extends BaseMigration
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
    $table = $this->table('messages');
    $table->addColumn('conversation_id', 'integer')
          ->addColumn('sender', 'enum', ['values' => ['user','bot']])
          ->addColumn('message', 'text')
          ->addColumn('created', 'datetime')
          ->addForeignKey('conversation_id', 'conversations', 'id', ['delete' => 'CASCADE'])
          ->create();
}
}
