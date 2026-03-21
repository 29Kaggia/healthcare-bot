<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateReports extends BaseMigration
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
    $table = $this->table('reports');
    $table->addColumn('user_id', 'integer')
          ->addColumn('conversation_id', 'integer')
          ->addColumn('file_path', 'string', ['limit' => 255])
          ->addColumn('created', 'datetime')
          ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
          ->addForeignKey('conversation_id', 'conversations', 'id', ['delete' => 'CASCADE'])
          ->create();
}
}
