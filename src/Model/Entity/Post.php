<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $published
 * @property string|null $short_description
 * @property string|null $description
 * @property string $meta_title
 * @property string|null $meta_description
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\ORM\Entity|null $title_translation
 * @property \Cake\ORM\Entity|null $slug_translation
 * @property \Cake\ORM\Entity|null $short_description_translation
 * @property \Cake\ORM\Entity|null $description_translation
 * @property \Cake\ORM\Entity|null $meta_title_translation
 * @property \Cake\ORM\Entity|null $meta_description_translation
 * @property \Cake\ORM\Entity[] $_i18n
 */
class Post extends Entity
{
}
