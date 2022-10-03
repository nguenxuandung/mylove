<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Text;

/**
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \Cake\ORM\Behavior\TranslateBehavior
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasOne $Pages_title_translation
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasOne $Pages_content_translation
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasOne $Pages_meta_title_translation
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasOne $Pages_meta_description_translation
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasMany $I18n
 * @method \App\Model\Entity\Page get($primaryKey, $options = [])
 * @method \App\Model\Entity\Page newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Page[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Page|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Page saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Page patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Page[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Page findOrCreate($search, callable $callback = null, $options = [])
 * @method \App\Model\Entity\Page[]|\Cake\Datasource\ResultSetInterface|false saveMany($entities, $options = [])
 * @method \App\Model\Entity\Page[]|\Cake\Datasource\ResultSetInterface saveManyOrFail($entities, $options = [])
 * @method \App\Model\Entity\Page[]|\Cake\Datasource\ResultSetInterface|false deleteMany($entities, $options = [])
 * @method \App\Model\Entity\Page[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail($entities, $options = [])
 */
class PagesTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
        $this->addBehavior('Translate', ['fields' => ['title', 'content', 'meta_title', 'meta_description']]);
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
            ->allowEmptyString('content')
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
            $conditions['Pages.id <>'] = $id;
        }

        while ($this->find()->where($conditions)->count()) {
            if (!preg_match('/-{1}[0-9]+$/', $slug)) {
                $slug .= '-' . ++$i;
            } else {
                $slug = preg_replace('/[0-9]+$/', ++$i, $slug);
            }
            $conditions['Pages.slug'] = $slug;
        }

        return $slug;
    }
}
