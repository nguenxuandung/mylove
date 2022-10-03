<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \Cake\ORM\Behavior\TranslateBehavior
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasOne $Testimonials_name_translation
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasOne $Testimonials_position_translation
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasOne $Testimonials_content_translation
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasMany $I18n
 * @method \App\Model\Entity\Testimonial get($primaryKey, $options = [])
 * @method \App\Model\Entity\Testimonial newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Testimonial[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Testimonial|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Testimonial saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Testimonial patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Testimonial[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Testimonial findOrCreate($search, callable $callback = null, $options = [])
 * @method \App\Model\Entity\Testimonial[]|\Cake\Datasource\ResultSetInterface|false saveMany($entities, $options = [])
 * @method \App\Model\Entity\Testimonial[]|\Cake\Datasource\ResultSetInterface saveManyOrFail($entities, $options = [])
 * @method \App\Model\Entity\Testimonial[]|\Cake\Datasource\ResultSetInterface|false deleteMany($entities, $options = [])
 * @method \App\Model\Entity\Testimonial[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail($entities, $options = [])
 */
class TestimonialsTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
        $this->addBehavior('Translate', ['fields' => ['name', 'position', 'content']]);
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->notBlank('name')
            ->notBlank('position')
            ->notBlank('image')
            ->add('published', 'inList', [
                'rule' => ['inList', ['0', '1']],
                'message' => __('Choose a valid value.'),
            ])
            ->notBlank('content');

        return $validator;
    }
}
