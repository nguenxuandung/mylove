<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $published
 * @property string $content
 * @property string $meta_title
 * @property string|null $meta_description
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\ORM\Entity|null $title_translation
 * @property \Cake\ORM\Entity|null $content_translation
 * @property \Cake\ORM\Entity|null $meta_title_translation
 * @property \Cake\ORM\Entity|null $meta_description_translation
 * @property \Cake\ORM\Entity[] $_i18n
 */
class Page extends Entity
{
}
