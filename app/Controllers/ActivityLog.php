<?php

namespace App\Controllers;

use App\Models\ActivityLogModel;
use CodeIgniter\Database\BaseConnection;
use Config\Database;

class ActivityLog extends BaseController
{
    protected ActivityLogModel $activityModel;
    protected BaseConnection $db;

    public function __construct()
    {
        $this->activityModel = new ActivityLogModel();
        $this->db = Database::connect();
    }

    /**
     * Display activity log dashboard
     */
    public function index()
    {
        $role = session()->get('role');

        // Only admin can access activity log
        if ($role !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Hanya admin yang dapat melihat activity log.');
        }

        $page  = (int) ($this->request->getGet('page') ?? 1);
        $limit = 50;
        $offset = ($page - 1) * $limit;

        // Get filter parameters
        $userId = $this->request->getGet('user_id');
        $module = $this->request->getGet('module');
        $action = $this->request->getGet('action');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        // Get activities with filters
        $activities = $this->activityModel->getActivities(
            userId: $userId ? (int) $userId : null,
            module: $module,
            action: $action,
            startDate: $startDate,
            endDate: $endDate,
            limit: $limit,
            offset: $offset
        );

        $totalActivities = $this->activityModel->countActivities(
            userId: $userId ? (int) $userId : null,
            module: $module,
            action: $action,
            startDate: $startDate,
            endDate: $endDate
        );

        $totalPages = ceil($totalActivities / $limit);

        // Get stats
        $stats = $this->activityModel->getStats($startDate, $endDate);

        // Get available modules and actions for filter
        $modules = $this->activityModel
            ->builder()
            ->select('DISTINCT module')
            ->orderBy('module', 'ASC')
            ->get()
            ->getResultArray();

        $actions = $this->activityModel
            ->builder()
            ->select('DISTINCT action')
            ->orderBy('action', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'activities'     => $activities,
            'totalActivities' => $totalActivities,
            'page'           => $page,
            'totalPages'     => $totalPages,
            'limit'          => $limit,
            'stats'          => $stats,
            'modules'        => $modules,
            'actions'        => $actions,
            'filters'        => [
                'user_id'   => $userId,
                'module'    => $module,
                'action'    => $action,
                'start_date' => $startDate,
                'end_date'   => $endDate,
            ],
        ];

        return view('admin/activity_log/index', $data);
    }

    /**
     * Export activity log as CSV
     */
    public function export()
    {
        $role = session()->get('role');

        if ($role !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }

        $userId = $this->request->getGet('user_id');
        $module = $this->request->getGet('module');
        $action = $this->request->getGet('action');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        // Get all activities (no limit for export)
        $activities = $this->activityModel->getActivities(
            userId: $userId ? (int) $userId : null,
            module: $module,
            action: $action,
            startDate: $startDate,
            endDate: $endDate,
            limit: 999999,
            offset: 0
        );

        // Generate CSV
        $filename = 'activity_log_' . date('Y-m-d_His') . '.csv';
        $csv = "ID,User ID,Username,Role,Module,Action,Description,Target Type,Target ID,IP Address,Created At\n";

        foreach ($activities as $activity) {
            $csv .= '"' . $activity['id'] . '",';
            $csv .= '"' . ($activity['user_id'] ?? '-') . '",';
            $csv .= '"' . addslashes($activity['username']) . '",';
            $csv .= '"' . $activity['role'] . '",';
            $csv .= '"' . $activity['module'] . '",';
            $csv .= '"' . $activity['action'] . '",';
            $csv .= '"' . addslashes($activity['description'] ?? '-') . '",';
            $csv .= '"' . ($activity['target_type'] ?? '-') . '",';
            $csv .= '"' . ($activity['target_id'] ?? '-') . '",';
            $csv .= '"' . $activity['ip_address'] . '",';
            $csv .= '"' . $activity['created_at'] . "\"\n";
        }

        return $this->response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csv);
    }

    /**
     * Get user activity detail
     */
    public function userDetail(int $userId)
    {
        $role = session()->get('role');

        if ($role !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }

        $page = (int) ($this->request->getGet('page') ?? 1);
        $limit = 50;
        $offset = ($page - 1) * $limit;

        $activities = $this->activityModel->getActivities(
            userId: $userId,
            limit: $limit,
            offset: $offset
        );

        $totalActivities = $this->activityModel->countActivities(userId: $userId);
        $totalPages = ceil($totalActivities / $limit);

        // Get user info
        $user = $this->db->table('users')
            ->where('id', $userId)
            ->get()
            ->getRowArray();

        if (! $user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $data = [
            'user'           => $user,
            'activities'     => $activities,
            'totalActivities' => $totalActivities,
            'page'           => $page,
            'totalPages'     => $totalPages,
            'limit'          => $limit,
        ];

        return view('admin/activity_log/user_detail', $data);
    }

    /**
     * Clear activity logs (keep for X days)
     */
    public function clearOldLogs()
    {
        $role = session()->get('role');

        if ($role !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Akses ditolak.',
            ]);
        }

        $days = (int) $this->request->getPost('days') ?? 90;

        $dateLimit = date('Y-m-d H:i:s', strtotime("-{$days} days"));

        $this->activityModel->where('created_at <', $dateLimit)->delete();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Activity log lama berhasil dihapus.',
        ]);
    }
}
