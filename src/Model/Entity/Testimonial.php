<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property string $name
 * @property string $position
 * @property string $image
 * @property bool|null $published
 * @property string|null $content
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\ORM\Entity|null $name_translation
 * @property \Cake\ORM\Entity|null $position_translation
 * @property \Cake\ORM\Entity|null $content_translation
 * @property \Cake\ORM\Entity[] $_i18n
 */
class Testimonial extends Entity
{
}
