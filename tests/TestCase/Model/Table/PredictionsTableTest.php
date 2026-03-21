<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PredictionsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PredictionsTable Test Case
 */
class PredictionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PredictionsTable
     */
    protected $Predictions;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Predictions',
        'app.Conversations',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Predictions') ? [] : ['className' => PredictionsTable::class];
        $this->Predictions = $this->getTableLocator()->get('Predictions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Predictions);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\PredictionsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\PredictionsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
