<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Withdraw get($primaryKey, $options = [])
 * @method \App\Model\Entity\Withdraw newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Withdraw[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Withdraw|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Withdraw saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Withdraw patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Withdraw[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Withdraw findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method \App\Model\Entity\Withdraw[]|\Cake\Datasource\ResultSetInterface|false saveMany($entities, $options = [])
 * @method \App\Model\Entity\Withdraw[]|\Cake\Datasource\ResultSetInterface saveManyOrFail($entities, $options = [])
 * @method \App\Model\Entity\Withdraw[]|\Cake\Datasource\ResultSetInterface|false deleteMany($entities, $options = [])
 * @method \App\Model\Entity\Withdraw[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail($entities, $options = [])
 */
class WithdrawsTable extends Table
{
    public function initialize(array $config)
    {
        $this->belongsTo('Users');
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->requirePresence('amount')
            ->notBlank('amount', __('You must have a balance.'));

        return $validator;
    }
}
