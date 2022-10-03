<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\HasMany $Users
 *
 * @method \App\Model\Entity\Plan get($primaryKey, $options = [])
 * @method \App\Model\Entity\Plan newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Plan[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Plan|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Plan saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Plan patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Plan[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Plan findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \Cake\ORM\Behavior\TranslateBehavior
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasMany $I18n
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasOne $Plans_title_translation
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasOne $Plans_description_translation
 * @method \App\Model\Entity\Plan[]|\Cake\Datasource\ResultSetInterface|false saveMany($entities, $options = [])
 * @method \App\Model\Entity\Plan[]|\Cake\Datasource\ResultSetInterface saveManyOrFail($entities, $options = [])
 * @method \App\Model\Entity\Plan[]|\Cake\Datasource\ResultSetInterface|false deleteMany($entities, $options = [])
 * @method \App\Model\Entity\Plan[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail($entities, $options = [])
 */
class PlansTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
        $this->hasMany('Users');
        $this->addBehavior('Translate', ['fields' => ['title', 'description']]);
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->notBlank('title')
            ->boolean('enable', __('Choose a valid value.'))
            ->numeric('monthly_price', __('Choose a valid value.'))
            ->numeric('yearly_price', __('Choose a valid value.'))
            ->boolean('edit_link', __('Choose a valid value.'))
            ->boolean('edit_long_url', __('Choose a valid value.'))
            ->boolean('ads', __('Choose a valid value.'))
            ->boolean('direct', __('Choose a valid value.'))
            ->boolean('alias', __('Choose a valid value.'))
            ->boolean('referral', __('Choose a valid value.'))
            ->boolean('stats', __('Choose a valid value.'))
            ->boolean('api_quick', __('Choose a valid value.'))
            ->boolean('api_mass', __('Choose a valid value.'))
            ->boolean('api_full', __('Choose a valid value.'))
            ->boolean('api_developer', __('Choose a valid value.'))
            ->notBlank('plan_replace', __('Choose a valid value.'));

        return $validator;
    }
}
