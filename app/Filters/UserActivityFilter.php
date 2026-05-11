<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Database;

class UserActivityFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $userId = (int) session()->get('user_id');

        if ($userId > 0) {
            $db = Database::connect();

            $db->table('users')
                ->where('id', $userId)
                ->update([
                    'is_online' => 1,
                    'last_seen' => date('Y-m-d H:i:s'),
                ]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // kosong
    }
}