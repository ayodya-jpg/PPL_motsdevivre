<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class OrdersProxy extends BaseController
{
    public function getOrders()
    {
        $client = \Config\Services::curlrequest();
        
        try {
            $response = $client->get('http://nginx_server/api/admin/orders', [
                'http_errors' => false,
                'timeout' => 30,
                'verify' => false
            ]);
            
            $statusCode = $response->getStatusCode();
            $body = $response->getBody();
            
            // Log untuk debugging
            log_message('info', "OrdersProxy - Status: {$statusCode}");
            log_message('info', "OrdersProxy - Body: {$body}");
            
            if ($statusCode == 200) {
                $data = json_decode($body);
                
                // Return JSON response
                return $this->response
                    ->setContentType('application/json')
                    ->setJSON($data)
                    ->setStatusCode(200);
            }
            
            return $this->response
                ->setContentType('application/json')
                ->setJSON(['error' => 'Failed to fetch orders', 'status' => $statusCode])
                ->setStatusCode($statusCode);
                
        } catch (\Exception $e) {
            log_message('error', 'OrdersProxy Error: ' . $e->getMessage());
            
            return $this->response
                ->setContentType('application/json')
                ->setJSON(['error' => $e->getMessage()])
                ->setStatusCode(500);
        }
    }
}
