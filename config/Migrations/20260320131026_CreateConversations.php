<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateConversations extends BaseMigration
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
    $table = $this->table('conversations');
    $table->addColumn('user_id', 'integer')
          ->addColumn('title', 'string', ['limit' => 255, 'null' => true])
          ->addTimestamps()
          ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
          ->create();
}
}
