<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property string $title
 * @property int $published
 * @property string|null $content
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\ORM\Entity|null $title_translation
 * @property \Cake\ORM\Entity|null $content_translation
 * @property \Cake\ORM\Entity[] $_i18n
 */
class Announcement extends Entity
{
}
