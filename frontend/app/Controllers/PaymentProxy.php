<?php

namespace App\Controllers;

class PaymentProxy extends BaseController
{
    public function checkout()
    {
        // Ambil data JSON dari request
        $input = $this->request->getJSON(true);
        
        // Validasi basic
        if (!$input || !isset($input['total'], $input['name'], $input['email'])) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'error' => 'Data tidak lengkap'
                ]);
        }
        
        $client = \Config\Services::curlrequest();
        
        try {
            // Forward request ke Laravel backend
            $response = $client->post('http://nginx_server/api/checkout/pay', [
                'json' => $input,
                'http_errors' => false,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]);
            
            $statusCode = $response->getStatusCode();
            $body = $response->getBody();
            
            // Return response dari backend
            return $this->response
                ->setStatusCode($statusCode)
                ->setContentType('application/json')
                ->setBody($body);
                
        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'error' => 'Gagal terhubung ke backend: ' . $e->getMessage()
                ]);
        }
    }
}