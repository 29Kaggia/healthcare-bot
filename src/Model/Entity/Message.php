<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Message Entity
 *
 * @property int $id
 * @property int $conversation_id
 * @property string $sender
 * @property string $message
 * @property \Cake\I18n\DateTime $created
 *
 * @property \App\Model\Entity\Conversation $conversation
 */
class Message extends Entity
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
        'conversation_id' => true,
        'sender' => true,
        'message' => true,
        'created' => true,
        'conversation' => true,
    ];
}
