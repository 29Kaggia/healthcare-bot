<?php
declare(strict_types=1);

namespace App\Controller;

class SettingsController extends AppController
{
    public function index()
    {
        $user = $this->request->getAttribute('identity');
        $fullUser = $this->getTableLocator()->get('Users')->get($user->id);

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $this->getTableLocator()->get('Users')->patchEntity($fullUser, [
                'age'        => $data['age'] ?? null,
                'gender'     => $data['gender'] ?? null,
                'conditions' => $data['conditions'] ?? null,
                'allergies'  => $data['allergies'] ?? null,
            ]);
            $this->getTableLocator()->get('Users')->save($fullUser);
            $this->Flash->success('Profile updated successfully.');
        }

        $this->set(compact('fullUser'));
    }
}
