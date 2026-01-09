<?php

namespace App\Controllers;

class SessionSync extends BaseController
{
    public function syncSession()
    {
        try {
            $email = $this->request->getPost('email');
            $userData = $this->request->getPost('user_data');
            
            if (!$email || !$userData) {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Data tidak lengkap'
                ]);
            }
            
            $userArray = json_decode($userData, true);
            
            if (!$userArray) {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Format data invalid'
                ]);
            }
            
            // Set session CI lengkap
            session()->set([
                'is_logged_in' => true,
                'user_id' => $userArray['id'] ?? null,
                'email' => $email,
                'name' => $userArray['name'] ?? '',
                'phone' => $userArray['phone'] ?? '',
                'is_subscribed' => $userArray['is_subscribed'] ?? false,
                'plan_name' => $userArray['plan_name'] ?? null
            ]);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Session synced'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'SessionSync error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }
}
