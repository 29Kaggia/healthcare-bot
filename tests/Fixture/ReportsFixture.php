<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ReportsFixture
 */
class ReportsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'user_id' => 1,
                'conversation_id' => 1,
                'file_path' => 'Lorem ipsum dolor sit amet',
                'created' => '2026-03-20 16:46:03',
            ],
        ];
        parent::init();
    }
}
