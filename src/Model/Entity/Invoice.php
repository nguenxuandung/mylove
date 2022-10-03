<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $user_id
 * @property int $status
 * @property string|null $description
 * @property int $type
 * @property int $rel_id
 * @property string $payment_method
 * @property float $amount
 * @property string|null $data
 * @property \Cake\I18n\FrozenTime|null $paid_date
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $created
 * @property int $id
 * @property \App\Model\Entity\User $user
 */
class Invoice extends Entity
{
}
