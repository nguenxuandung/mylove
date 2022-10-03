<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\CampaignItemsTable&\Cake\ORM\Association\HasMany $CampaignItems
 * @property \App\Model\Table\InvoicesTable&\Cake\ORM\Association\HasOne $Invoices
 * @property \App\Model\Table\StatisticsTable&\Cake\ORM\Association\HasMany $Statistics
 *
 * @method \Cake\ORM\Query findById($id)
 * @method \App\Model\Entity\Campaign get($primaryKey, $options = [])
 * @method \App\Model\Entity\Campaign newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Campaign[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Campaign|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Campaign saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Campaign patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Campaign[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Campaign findOrCreate($search, callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method \App\Model\Entity\Campaign[]|\Cake\Datasource\ResultSetInterface|false saveMany($entities, $options = [])
 * @method \App\Model\Entity\Campaign[]|\Cake\Datasource\ResultSetInterface saveManyOrFail($entities, $options = [])
 * @method \App\Model\Entity\Campaign[]|\Cake\Datasource\ResultSetInterface|false deleteMany($entities, $options = [])
 * @method \App\Model\Entity\Campaign[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail($entities, $options = [])
 */
class CampaignsTable extends Table
{
    public function initialize(array $config)
    {
        $this->belongsTo('Users');

        $this->hasMany('CampaignItems', [
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);

        $this->hasMany('Statistics');

        $this->hasOne('Invoices', [
            'foreignKey' => 'rel_id',
            'conditions' => ['type' => 2],
        ]);

        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->notBlank('user_id', __('This value should not be blank.'))
            ->add('status', 'inList', [
                'rule' => ['inList', [1, 2, 3, 4, 5, 6, 7, 8]],
                'message' => __('Choose a valid value.'),
            ])
            ->notBlank('name', __('This value should not be blank.'))
            ->notBlank('website_title', __('This value should not be blank.'))
            ->notBlank('website_url', __('This value should not be blank.'))
            ->add('website_url', 'url', [
                'rule' => 'url',
                'message' => __('URL must be valid.'),
            ])
            ->add('website_url', 'checkProtocol', [
                'rule' => function ($value, $context) {
                    $scheme = parse_url($value, PHP_URL_SCHEME);

                    if (in_array($scheme, ['http', 'https'])) {
                        return true;
                    }

                    return false;
                },
                'message' => __('http and https urls only allowed.'),
            ])
            /*
            ->add('website_url', 'checkXFrameOptions', [
                'rule' => function ($value, $context) {
                    $headers = get_http_headers( $value );
                    if ( isset( $headers[ "x-frame-options" ] ) ) {
                        return false;
                    }
                    return true;
                },
                'message' => __('This website URL refused to be used in interstitial ads.')
            ])
            */
            ->notBlank('banner_name', __('This value should not be blank.'))
            ->add('banner_size', 'inList', [
                'rule' => ['inList', ['728x90', '468x60', '336x280']],
                'message' => __('Choose a valid value.'),
            ])
            ->notBlank('banner_code', __('This value should not be blank.'))
            ->notBlank('price', __('You must have a purchase.'))
            ->add('traffic_source', 'inList', [
                'rule' => ['inList', [1, 2, 3]],
                'message' => __('Choose a valid value.'),
            ])
            ->add('website_terms', 'termsAccept', [
                'rule' => function ($value, $context) {
                    if ($value == 1) {
                        return true;
                    }

                    return false;
                },
                'message' => __('You must accept our terms and conditions.'),
            ])
            ->add('payment_method', 'inList', [
                'rule' => [
                    'inList',
                    [
                        'paypal',
                        'skrill',
                        'coinpayments',
                        'coinbase',
                        'webmoney',
                        'banktransfer',
                        'wallet',
                    ],
                ],
                'message' => __('Choose a valid value.'),
            ]);

        return $validator;
    }

    public function isOwnedBy($id, $user_id)
    {
        return $this->exists(['id' => $id, 'user_id' => $user_id]);
    }
}
