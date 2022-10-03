<?php

namespace App\Model\Table;

use ADmad\SocialAuth\Model\Table\SocialProfilesTable as SocialProfilesTablePlugin;

/**
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\SocialProfile get($primaryKey, $options = [])
 * @method \App\Model\Entity\SocialProfile newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SocialProfile[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SocialProfile|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SocialProfile saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SocialProfile patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SocialProfile[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SocialProfile findOrCreate($search, callable $callback = null, $options = [])
 * @method \App\Model\Entity\SocialProfile[]|\Cake\Datasource\ResultSetInterface|false saveMany($entities, $options = [])
 * @method \App\Model\Entity\SocialProfile[]|\Cake\Datasource\ResultSetInterface saveManyOrFail($entities, $options = [])
 * @method \App\Model\Entity\SocialProfile[]|\Cake\Datasource\ResultSetInterface|false deleteMany($entities, $options = [])
 * @method \App\Model\Entity\SocialProfile[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail($entities, $options = [])
 */
class SocialProfilesTable extends SocialProfilesTablePlugin
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->belongsTo('Users');
    }
}
