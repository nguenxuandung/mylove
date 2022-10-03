<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Text;

/**
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \Cake\ORM\Behavior\TranslateBehavior
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasOne $Posts_title_translation
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasOne $Posts_slug_translation
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasOne $Posts_short_description_translation
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasOne $Posts_description_translation
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasOne $Posts_meta_title_translation
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasOne $Posts_meta_description_translation
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasMany $I18n
 * @method \App\Model\Entity\Post get($primaryKey, $options = [])
 * @method \App\Model\Entity\Post newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Post[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Post|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Post saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Post patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Post[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Post findOrCreate($search, callable $callback = null, $options = [])
 * @method \App\Model\Entity\Post[]|\Cake\Datasource\ResultSetInterface|false saveMany($entities, $options = [])
 * @method \App\Model\Entity\Post[]|\Cake\Datasource\ResultSetInterface saveManyOrFail($entities, $options = [])
 * @method \App\Model\Entity\Post[]|\Cake\Datasource\ResultSetInterface|false deleteMany($entities, $options = [])
 * @method \App\Model\Entity\Post[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail($entities, $options = [])
 */
class PostsTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
        $this->addBehavior('Translate', [
            'fields' => [
                'title',
                'slug',
                'short_description',
                'description',
                'meta_title',
                'meta_description',
            ],
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->notBlank('title')
            ->allowEmptyString('slug')
            ->add('slug', [
                'unique' => [
                    'rule' => 'validateUnique',
                    'provider' => 'table',
                    'message' => __('Slug must be unique.'),
                ],
            ])
            ->add('published', 'inList', [
                'rule' => ['inList', ['0', '1']],
                'message' => __('Choose a valid value.'),
            ])
            ->allowEmptyString('short_description')
            ->allowEmptyString('description')
            ->allowEmptyString('meta_title')
            ->allowEmptyString('meta_description');

        return $validator;
    }

    //http://www.whatstyle.net/articles/52/generate_unique_slugs_in_cakephp
    public function createSlug($slug, $id = null)
    {
        $slug = mb_strtolower(Text::slug($slug, '-'));
        $i = 0;
        $conditions = [];
        $conditions['slug'] = $slug;
        if (!is_null($id)) {
            $conditions['Posts.id <>'] = $id;
        }

        while ($this->find()->where($conditions)->count()) {
            if (!preg_match('/-{1}[0-9]+$/', $slug)) {
                $slug .= '-' . ++$i;
            } else {
                $slug = preg_replace('/[0-9]+$/', ++$i, $slug);
            }
            $conditions['Posts.slug'] = $slug;
        }

        return $slug;
    }
}
