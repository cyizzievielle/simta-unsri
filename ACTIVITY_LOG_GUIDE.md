# Activity Log / Audit Trail Documentation

## Overview
Activity Log adalah sistem untuk melacak semua aktivitas pengguna dalam sistem. Setiap action yang penting akan dicatat dengan detail:
- **Siapa** (User ID, Username, Role)
- **Ngapain** (Action, Module)
- **Kapan** (Timestamp)
- **Di mana** (IP Address)
- **Apa yang diubah** (Target Type, Target ID, Description)

---

## Setup

### 1. Jalankan Migration
```bash
php spark migrate
```

Ini akan membuat table `activity_logs` dengan struktur:
- `id` - Primary key
- `user_id` - ID user yang melakukan action
- `username` - Username
- `role` - Role user (admin, mahasiswa, dosen)
- `module` - Module/bagian sistem (chat, proposal, profile, user, admin)
- `action` - Action yang dilakukan (create, update, delete, view, send, download, etc)
- `description` - Detail/deskripsi action
- `target_type` - Tipe target yang diubah (user, proposal, message, etc)
- `target_id` - ID target
- `ip_address` - IP address user
- `user_agent` - Browser user agent
- `created_at` - Timestamp

---

## Penggunaan

### Cara 1: Menggunakan Helper Function (Recommended)

Setelah helper di-load, kamu bisa menggunakan function `activity_log()` di mana saja:

```php
// Generic logging
activity_log('chat', 'send', 'Mengirim pesan ke room #123', 'message', 123);

// Atau gunakan helper khusus untuk modul tertentu
activity_log('proposal', 'upload', 'Upload proposal judul: Implementasi AI', 'proposal', 45);
activity_log('user', 'login', 'User berhasil login', 'user', 1);
activity_log('profile', 'update', 'Update foto profil', 'profile', 1);
```

### Cara 2: Menggunakan Model Langsung

```php
use App\Models\ActivityLogModel;

$activityModel = new ActivityLogModel();
$activityModel->log('module', 'action', 'description', 'target_type', 123);
```

### Cara 3: Menggunakan ActivityHelper Class

```php
use App\Helpers\ActivityHelper;

// Chat actions
ActivityHelper::logChatAction('send', 'Pesan dikirim ke room #5', 5);
ActivityHelper::logChatAction('delete', 'Pesan dihapus', 123);

// Proposal actions
ActivityHelper::logProposalAction('upload', 'Proposal tahap 1 diupload', 10);
ActivityHelper::logProposalAction('review', 'Proposal direview - Lolos', 10);

// User actions
ActivityHelper::logUserAction('create', 'User mahasiswa baru ditambah', 45);
ActivityHelper::logUserAction('delete', 'User dosen dihapus', 23);

// Profile actions
ActivityHelper::logProfileAction('update', 'Update foto profil', 1);

// Admin actions
ActivityHelper::logAdminAction('create', 'Periode akademik 2024/2025 dibuat', 1);
```

---

## Contoh Implementasi di Controller

### Chat Controller

```php
<?php
namespace App\Controllers;

use App\Helpers\ActivityHelper;

class Chat extends BaseController
{
    public function send()
    {
        // ... validasi dan insert ke database ...
        
        // Log activity
        ActivityHelper::logChatAction(
            'send',
            'Pesan dikirim ke room #' . $roomId,
            $messageId
        );
        
        return redirect()->back();
    }

    public function delete()
    {
        // ... delete logic ...
        
        ActivityHelper::logChatAction(
            'delete',
            'Pesan dihapus dari room #' . $roomId,
            $messageId
        );
        
        return redirect()->back();
    }
}
```

### Proposal Controller

```php
<?php
namespace App\Controllers;

use App\Helpers\ActivityHelper;

class Proposal extends BaseController
{
    public function upload()
    {
        // ... upload logic ...
        
        ActivityHelper::logProposalAction(
            'upload',
            'File proposal: ' . $file->getClientName(),
            $proposalId
        );
        
        return redirect()->back()->with('success', 'Proposal berhasil diupload');
    }

    public function review()
    {
        // ... review logic ...
        
        $status = $this->request->getPost('status'); // approved, revision, rejected
        
        ActivityHelper::logProposalAction(
            'review',
            'Status proposal: ' . $status . ' - ' . $this->request->getPost('catatan'),
            $proposalId
        );
        
        return redirect()->back();
    }
}
```

### Admin Controller (User Management)

```php
<?php
namespace App\Controllers;

use App\Helpers\ActivityHelper;

class Admin extends BaseController
{
    public function storeUser()
    {
        // ... validation dan insert ...
        
        ActivityHelper::logUserAction(
            'create',
            'User ' . $role . ': ' . $email . ' dibuat',
            $userId
        );
        
        return redirect()->to('admin/users')->with('success', 'User berhasil dibuat');
    }

    public function updateUser(int $id)
    {
        // ... update logic ...
        
        ActivityHelper::logUserAction(
            'update',
            'User #' . $id . ' diupdate - ' . $email,
            $id
        );
        
        return redirect()->back();
    }

    public function deleteUser(int $id)
    {
        // ... delete logic ...
        
        ActivityHelper::logUserAction(
            'delete',
            'User dihapus - ' . $email,
            $id
        );
        
        return redirect()->back();
    }
}
```

### Profile Controller

```php
<?php
namespace App\Controllers;

use App\Helpers\ActivityHelper;

class Profile extends BaseController
{
    public function update()
    {
        // ... update profile ...
        
        ActivityHelper::logProfileAction(
            'update',
            'Update profil: foto, nama, atau data lainnya',
            $userId
        );
        
        return redirect()->back();
    }
}
```

---

## Mengakses Activity Log Dashboard

### Admin Dashboard
Buka menu: **Admin → Activity Log / Audit Trail**

URL: `/admin/activity-log`

### Fitur:
1. **View Activities**
   - Lihat semua aktivitas dengan pagination
   - Filter by module, action, date range
   
2. **Export CSV**
   - Export filtered activities ke CSV
   
3. **User Detail**
   - Klik username untuk lihat detail semua activity user tersebut
   
4. **Statistics**
   - Total activities
   - Module distribution
   - Top active users
   - Role distribution

---

## Query Activity Log Programmatically

```php
use App\Models\ActivityLogModel;

$activityModel = new ActivityLogModel();

// Get activities dengan filter
$activities = $activityModel->getActivities(
    userId: 5,                           // Optional: user_id
    module: 'proposal',                  // Optional: module
    action: 'upload',                    // Optional: action
    startDate: '2024-05-01',             // Optional
    endDate: '2024-05-31',               // Optional
    limit: 50,
    offset: 0
);

// Count activities
$count = $activityModel->countActivities(
    module: 'chat',
    action: 'send'
);

// Get statistics
$stats = $activityModel->getStats(
    startDate: '2024-05-01',
    endDate: '2024-05-31'
);

// Result:
// [
//   'total_activities' => 1250,
//   'by_module' => [
//     ['module' => 'chat', 'count' => 500],
//     ['module' => 'proposal', 'count' => 450],
//     ...
//   ],
//   'by_action' => [...],
//   'by_user' => [...],
//   'by_role' => [...]
// ]
```

---

## Best Practices

### 1. Jangan Log Terlalu Banyak
Fokus pada aktivitas yang penting saja:
- ✅ Create, Update, Delete
- ✅ Upload files
- ✅ Approve/Reject actions
- ❌ Jangan log setiap kali page dimuat
- ❌ Jangan log read/view action yang tidak penting

### 2. Gunakan Description yang Jelas
```php
// Baik
ActivityHelper::logProposalAction('upload', 'Proposal Bab 1-3 - File: proposal_v2.pdf', 10);

// Kurang baik
ActivityHelper::logProposalAction('upload', 'Upload', 10);
```

### 3. Selalu Include Target ID
Ini membantu tracing:
```php
// Baik
ActivityHelper::logChatAction('delete', 'Pesan dihapus', $messageId);

// Kurang baik
ActivityHelper::logChatAction('delete', 'Pesan dihapus', null);
```

### 4. Catat Perubahan Signifikan
```php
// Baik - catat apa yang berubah
$oldStatus = $current['status'];
$newStatus = $this->request->getPost('status');
ActivityHelper::logProposalAction(
    'update',
    "Status berubah dari {$oldStatus} ke {$newStatus}",
    $proposalId
);

// Kurang baik
ActivityHelper::logProposalAction('update', 'Updated', $proposalId);
```

---

## Maintenance

### Cleanup Old Logs
Untuk menghapus activity logs yang sudah lama (default: 90 hari):

```php
// Via API
POST /admin/activity-log/clear-old
{
  "days": 90
}

// Or via code
use App\Models\ActivityLogModel;
$activityModel = new ActivityLogModel();
$dateLimit = date('Y-m-d H:i:s', strtotime("-90 days"));
$activityModel->where('created_at <', $dateLimit)->delete();
```

---

## Troubleshooting

### Activity tidak tercatat?
1. Pastikan session terisi dengan `user_id` dan `username`
2. Pastikan migration sudah dijalankan
3. Cek database apakah table `activity_logs` sudah ada

### Query lambat?
Indexes sudah dibuat otomatis pada:
- `user_id`
- `module`
- `action`
- `created_at`
- `user_id + created_at` (composite)

Kalau masih lambat, bisa tambah index:
```php
$this->forge->addKey(['module', 'action']);
```

---

## Kesimpulan

Activity Log membantu admin untuk:
- 📊 Track penggunaan sistem
- 🔍 Audit trail untuk compliance
- 🚨 Detect suspicious activities
- 📈 Understand user behavior
- 🐛 Debugging & troubleshooting

Implementasi mudah dan scalable! 🚀
