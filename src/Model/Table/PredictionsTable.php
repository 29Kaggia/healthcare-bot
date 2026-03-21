<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Predictions Model
 *
 * @property \App\Model\Table\ConversationsTable&\Cake\ORM\Association\BelongsTo $Conversations
 *
 * @method \App\Model\Entity\Prediction newEmptyEntity()
 * @method \App\Model\Entity\Prediction newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Prediction> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Prediction get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Prediction findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Prediction patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Prediction> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Prediction|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Prediction saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Prediction>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Prediction>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Prediction>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Prediction> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Prediction>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Prediction>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Prediction>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Prediction> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PredictionsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('predictions');
        $this->setDisplayField('disease');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Conversations', [
            'foreignKey' => 'conversation_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('conversation_id')
            ->notEmptyString('conversation_id');

        $validator
            ->scalar('disease')
            ->maxLength('disease', 255)
            ->requirePresence('disease', 'create')
            ->notEmptyString('disease');

        $validator
            ->numeric('confidence')
            ->requirePresence('confidence', 'create')
            ->notEmptyString('confidence');

        $validator
            ->scalar('advice')
            ->requirePresence('advice', 'create')
            ->notEmptyString('advice');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['conversation_id'], 'Conversations'), ['errorField' => 'conversation_id']);

        return $rules;
    }
}
