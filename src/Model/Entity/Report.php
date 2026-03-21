<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Report Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $conversation_id
 * @property string $file_path
 * @property \Cake\I18n\DateTime $created
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Conversation $conversation
 */
class Report extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'user_id' => true,
        'conversation_id' => true,
        'file_path' => true,
        'created' => true,
        'user' => true,
        'conversation' => true,
    ];
}
