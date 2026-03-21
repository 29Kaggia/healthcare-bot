<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateSymptoms extends BaseMigration
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
    $table = $this->table('symptoms');
    $table->addColumn('user_id', 'integer')
          ->addColumn('conversation_id', 'integer')
          ->addColumn('symptoms', 'text')
          ->addColumn('created', 'datetime')
          ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
          ->addForeignKey('conversation_id', 'conversations', 'id', ['delete' => 'CASCADE'])
          ->create();
}
}
