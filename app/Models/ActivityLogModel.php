<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table            = 'activity_logs';
    protected $primaryKey        = 'id';
    protected $useAutoIncrement  = true;
    protected $returnType        = 'array';
    protected $useSoftDeletes    = false;
    protected $protectFields     = true;
    protected $allowedFields     = [
        'user_id',
        'username',
        'role',
        'module',
        'action',
        'description',
        'target_type',
        'target_id',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = null;

    /**
     * Catat aktivitas user
     */
    public function log(
        string $module,
        string $action,
        ?string $description = null,
        ?string $targetType = null,
        ?int $targetId = null
    ): bool {
        $userId  = (int) session()->get('user_id');
        $username = (string) session()->get('username');
        $role    = (string) session()->get('role');

        $data = [
            'user_id'     => $userId ?: null,
            'username'    => $username ?: 'guest',
            'role'        => $role ?: 'guest',
            'module'      => $module,
            'action'      => $action,
            'description' => $description,
            'target_type' => $targetType,
            'target_id'   => $targetId,
            'ip_address'  => $this->getIpAddress(),
            'user_agent'  => substr($this->getUserAgent(), 0, 500),
        ];

        return (bool) $this->insert($data);
    }

    /**
     * Get IP Address
     */
    protected function getIpAddress(): string
    {
        if (! empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (! empty($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }
        return '0.0.0.0';
    }

    /**
     * Get User Agent
     */
    protected function getUserAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    }

    /**
     * Get activities filter by multiple criteria
     */
    public function getActivities(
        ?int $userId = null,
        ?string $module = null,
        ?string $action = null,
        ?string $startDate = null,
        ?string $endDate = null,
        int $limit = 50,
        int $offset = 0
    ) {
        $builder = $this->builder();

        if ($userId !== null) {
            $builder->where('user_id', $userId);
        }

        if ($module !== null) {
            $builder->where('module', $module);
        }

        if ($action !== null) {
            $builder->where('action', $action);
        }

        if ($startDate !== null) {
            $builder->where('created_at >=', $startDate);
        }

        if ($endDate !== null) {
            $builder->where('created_at <=', $endDate);
        }

        return $builder
            ->orderBy('created_at', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
    }

    /**
     * Count activities
     */
    public function countActivities(
        ?int $userId = null,
        ?string $module = null,
        ?string $action = null,
        ?string $startDate = null,
        ?string $endDate = null
    ): int {
        $builder = $this->builder();

        if ($userId !== null) {
            $builder->where('user_id', $userId);
        }

        if ($module !== null) {
            $builder->where('module', $module);
        }

        if ($action !== null) {
            $builder->where('action', $action);
        }

        if ($startDate !== null) {
            $builder->where('created_at >=', $startDate);
        }

        if ($endDate !== null) {
            $builder->where('created_at <=', $endDate);
        }

        return $builder->countAllResults();
    }

    /**
     * Get activity stats
     */
    public function getStats(?string $startDate = null, ?string $endDate = null)
    {
        $builder = $this->builder();

        if ($startDate !== null) {
            $builder->where('created_at >=', $startDate);
        }

        if ($endDate !== null) {
            $builder->where('created_at <=', $endDate);
        }

        return [
            'total_activities'  => $builder->countAllResults(),
            'by_module'         => $this->getStatsByModule($startDate, $endDate),
            'by_action'         => $this->getStatsByAction($startDate, $endDate),
            'by_user'           => $this->getTopUsers($startDate, $endDate),
            'by_role'           => $this->getStatsByRole($startDate, $endDate),
        ];
    }

    /**
     * Get stats grouped by module
     */
    protected function getStatsByModule(?string $startDate = null, ?string $endDate = null)
    {
        $builder = $this->builder();

        if ($startDate !== null) {
            $builder->where('created_at >=', $startDate);
        }

        if ($endDate !== null) {
            $builder->where('created_at <=', $endDate);
        }

        return $builder
            ->select('module, COUNT(*) as count')
            ->groupBy('module')
            ->orderBy('count', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get stats grouped by action
     */
    protected function getStatsByAction(?string $startDate = null, ?string $endDate = null)
    {
        $builder = $this->builder();

        if ($startDate !== null) {
            $builder->where('created_at >=', $startDate);
        }

        if ($endDate !== null) {
            $builder->where('created_at <=', $endDate);
        }

        return $builder
            ->select('action, COUNT(*) as count')
            ->groupBy('action')
            ->orderBy('count', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get top active users
     */
    protected function getTopUsers(?string $startDate = null, ?string $endDate = null, int $limit = 10)
    {
        $builder = $this->builder();

        if ($startDate !== null) {
            $builder->where('created_at >=', $startDate);
        }

        if ($endDate !== null) {
            $builder->where('created_at <=', $endDate);
        }

        return $builder
            ->select('user_id, username, role, COUNT(*) as count')
            ->groupBy('user_id')
            ->orderBy('count', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Get stats grouped by role
     */
    protected function getStatsByRole(?string $startDate = null, ?string $endDate = null)
    {
        $builder = $this->builder();

        if ($startDate !== null) {
            $builder->where('created_at >=', $startDate);
        }

        if ($endDate !== null) {
            $builder->where('created_at <=', $endDate);
        }

        return $builder
            ->select('role, COUNT(*) as count')
            ->groupBy('role')
            ->orderBy('count', 'DESC')
            ->get()
            ->getResultArray();
    }
}
