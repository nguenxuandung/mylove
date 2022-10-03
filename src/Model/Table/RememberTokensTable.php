<?php

namespace App\Model\Table;

use Cake\ORM\Table;

/**
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\RememberToken get($primaryKey, $options = [])
 * @method \App\Model\Entity\RememberToken newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\RememberToken[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RememberToken|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RememberToken saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RememberToken patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\RememberToken[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\RememberToken findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method \App\Model\Entity\RememberToken[]|\Cake\Datasource\ResultSetInterface|false saveMany($entities, $options = [])
 * @method \App\Model\Entity\RememberToken[]|\Cake\Datasource\ResultSetInterface saveManyOrFail($entities, $options = [])
 * @method \App\Model\Entity\RememberToken[]|\Cake\Datasource\ResultSetInterface|false deleteMany($entities, $options = [])
 * @method \App\Model\Entity\RememberToken[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail($entities, $options = [])
 */
class RememberTokensTable extends Table
{
    public function initialize(array $config)
    {
        $this->belongsTo('Users');
        $this->addBehavior('Timestamp');
    }
}
