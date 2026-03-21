<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Prediction Entity
 *
 * @property int $id
 * @property int $conversation_id
 * @property string $disease
 * @property float $confidence
 * @property string $advice
 * @property \Cake\I18n\DateTime $created
 *
 * @property \App\Model\Entity\Conversation $conversation
 */
class Prediction extends Entity
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
        'disease' => true,
        'confidence' => true,
        'advice' => true,
        'created' => true,
        'conversation' => true,
    ];
}
