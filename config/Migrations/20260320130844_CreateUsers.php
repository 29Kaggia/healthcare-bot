<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateUsers extends BaseMigration
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
    $table = $this->table('users');
    $table->addColumn('name', 'string', ['limit' => 255])
          ->addColumn('email', 'string', ['limit' => 255])
          ->addColumn('password', 'string', ['limit' => 255])
          ->addTimestamps()
          ->addIndex(['email'], ['unique' => true])
          ->create();
}
}
