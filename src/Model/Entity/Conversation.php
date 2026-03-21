<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Conversation Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $title
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $updated
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Message[] $messages
 * @property \App\Model\Entity\Prediction[] $predictions
 * @property \App\Model\Entity\Report[] $reports
 * @property \App\Model\Entity\Symptom[] $symptoms
 */
class Conversation extends Entity
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
        'title' => true,
        'created' => true,
        'updated' => true,
        'user' => true,
        'messages' => true,
        'predictions' => true,
        'reports' => true,
        'symptoms' => true,
    ];
}
