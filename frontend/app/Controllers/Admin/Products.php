<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Products extends BaseController
{
    private $api_url = 'http://localhost:8000/api/products';

    // 1. FORM TAMBAH PRODUK
    public function create()
    {
        return view('admin/products/create', ['title' => 'Tambah Produk']);
    }

    // 2. PROSES SIMPAN (STORE)
    public function store()
    {
        $client = \Config\Services::curlrequest();

        // Ambil data input
        $postData = [
            'nama_produk' => $this->request->getPost('nama_produk'),
            'harga'       => $this->request->getPost('harga'),
            'stok'        => $this->request->getPost('stok'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
        ];

        // Ambil File Gambar
        $img = $this->request->getFile('gambar');
        if ($img && $img->isValid()) {
            // Siapkan file untuk dikirim via CURL
            $postData['gambar'] = new \CURLFile($img->getTempName(), $img->getClientMimeType(), $img->getName());
        }

        try {
            // Kirim sebagai Multipart Form Data
            $response = $client->post($this->api_url, [
                'multipart' => $postData
            ]);
            
            return redirect()->to('/admin/dashboard')->with('success', 'Produk berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambah produk: ' . $e->getMessage());
        }
    }

    // 3. FORM EDIT PRODUK
    public function edit($id)
    {
        $client = \Config\Services::curlrequest();
        try {
            $response = $client->get($this->api_url . '/' . $id);
            $body = json_decode($response->getBody());
            $product = $body->data;
        } catch (\Exception $e) {
            return redirect()->to('/admin/dashboard')->with('error', 'Produk tidak ditemukan');
        }

        return view('admin/products/edit', ['title' => 'Edit Produk', 'product' => $product]);
    }

    // 4. PROSES UPDATE
    public function update($id)
    {
        $client = \Config\Services::curlrequest();

        $postData = [
            'nama_produk' => $this->request->getPost('nama_produk'),
            'harga'       => $this->request->getPost('harga'),
            'stok'        => $this->request->getPost('stok'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            '_method'     => 'PUT' // TRICK: Laravel butuh ini untuk update via POST multipart
        ];

        $img = $this->request->getFile('gambar');
        if ($img && $img->isValid()) {
            $postData['gambar'] = new \CURLFile($img->getTempName(), $img->getClientMimeType(), $img->getName());
        }

        try {
            // Kita pakai POST tapi kirim _method=PUT di body agar Laravel menganggapnya PUT
            // Ini workaround karena cURL sulit kirim file via method PUT asli
            $response = $client->post($this->api_url . '/' . $id, [
                'multipart' => $postData
            ]);
            
            return redirect()->to('/admin/dashboard')->with('success', 'Produk berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal update produk.');
        }
    }

    // 5. PROSES DELETE
    public function delete($id)
    {
        $client = \Config\Services::curlrequest();
        try {
            $client->delete($this->api_url . '/' . $id);
            return redirect()->to('/admin/dashboard')->with('success', 'Produk berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->to('/admin/dashboard')->with('error', 'Gagal menghapus produk');
        }
    }
}