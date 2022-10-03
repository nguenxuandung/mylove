<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * @method \App\Model\Entity\Option get($primaryKey, $options = [])
 * @method \App\Model\Entity\Option newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Option[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Option|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Option saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Option patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Option[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Option findOrCreate($search, callable $callback = null, $options = [])
 * @property \Cake\ORM\Table|\Cake\ORM\Association\HasOne $Options_value_translation
 * @property \Cake\ORM\Table|\Cake\ORM\Association\HasMany $I18n
 * @mixin \Cake\ORM\Behavior\TranslateBehavior
 * @method \App\Model\Entity\Option[]|\Cake\Datasource\ResultSetInterface|false saveMany($entities, $options = [])
 * @method \App\Model\Entity\Option[]|\Cake\Datasource\ResultSetInterface saveManyOrFail($entities, $options = [])
 * @method \App\Model\Entity\Option[]|\Cake\Datasource\ResultSetInterface|false deleteMany($entities, $options = [])
 * @method \App\Model\Entity\Option[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail($entities, $options = [])
 */
class OptionsTable extends Table
{
    public function validationDefault(Validator $validator)
    {
        $validator
            ->notEmpty('name')
            ->add('name', [
                'alphaNumeric' => [
                    'rule' => ['custom', '/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/'],
                    'message' => 'Option name must only contain letters, numbers, underscore and start with letter.',
                ],
            ])
            ->add('name', [
                'unique' => [
                    'rule' => 'validateUnique',
                    'provider' => 'table',
                    'message' => 'Option\'s name already exists',
                ],
            ]);

        return $validator;
    }
}
