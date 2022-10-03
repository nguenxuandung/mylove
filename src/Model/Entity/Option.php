<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property string $name
 * @property string $value
 * @property \Cake\ORM\Entity $value_translation
 * @property \Cake\ORM\Entity[] $_i18n
 */
class Option extends Entity
{
}
