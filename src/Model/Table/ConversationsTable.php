<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Conversations Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\MessagesTable&\Cake\ORM\Association\HasMany $Messages
 * @property \App\Model\Table\PredictionsTable&\Cake\ORM\Association\HasMany $Predictions
 * @property \App\Model\Table\ReportsTable&\Cake\ORM\Association\HasMany $Reports
 * @property \App\Model\Table\SymptomsTable&\Cake\ORM\Association\HasMany $Symptoms
 *
 * @method \App\Model\Entity\Conversation newEmptyEntity()
 * @method \App\Model\Entity\Conversation newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Conversation> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Conversation get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Conversation findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Conversation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Conversation> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Conversation|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Conversation saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Conversation>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Conversation>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Conversation>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Conversation> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Conversation>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Conversation>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Conversation>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Conversation> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ConversationsTable extends Table
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

        $this->setTable('conversations');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Messages', [
            'foreignKey' => 'conversation_id',
        ]);
        $this->hasMany('Predictions', [
            'foreignKey' => 'conversation_id',
        ]);
        $this->hasMany('Reports', [
            'foreignKey' => 'conversation_id',
        ]);
        $this->hasMany('Symptoms', [
            'foreignKey' => 'conversation_id',
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
            ->integer('user_id')
            ->notEmptyString('user_id');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->allowEmptyString('title');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
