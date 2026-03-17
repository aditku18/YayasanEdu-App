# Rencana Restrukturisasi Arsitektur Modular

## YusufEdu-App - Modular Architecture Refactoring Plan

---

## 1. Tujuan

Merestrukturisasi sistem dari arsitektur "flat" menjadi **modular plugin system** yang:
- Memisahkan setiap domain bisnis ke modul masing-masing
- Memudahkan maintenance dan development
- Memungkinkan add-on development
- Struktur lebih rapi dan scalable

---

## 2. Struktur Folder Target

### 2.1 Struktur Utama

```
YayasanEdu-App/
├── app/
│   ├── Core/
│   │   ├── Base/
│   │   │   ├── BaseController.php
│   │   │   ├── BaseModel.php
│   │   │   └── BaseService.php
│   │   ├── Traits/
│   │   │   ├── HasAuditLog.php
│   │   │   ├── HasUuid.php
│   │   │   └── ScopeActive.php
│   │   ├── Helpers/
│   │   │   ├── TenantHelper.php (existing)
│   │   │   └── ResponseHelper.php
│   │   └── Exceptions/
│   │       └── Handler.php
│   │
│   ├── Modules/
│   │   ├── User/
│   │   │   ├── Http/
│   │   │   │   ├── Controllers/
│   │   │   │   │   ├── UserController.php
│   │   │   │   │   └── RoleController.php
│   │   │   │   └── Requests/
│   │   │   │       └── UserRequest.php
│   │   │   ├── Models/
│   │   │   │   ├── User.php
│   │   │   │   └── Role.php
│   │   │   ├── Routes/
│   │   │   │   └── web.php
│   │   │   ├── Views/
│   │   │   │   └── index.blade.php
│   │   │   └── Database/
│   │   │       └── migrations/
│   │   │
│   │   ├── Academic/
│   │   │   ├── Http/Controllers/
│   │   │   ├── Models/
│   │   │   │   ├── Student.php
│   │   │   │   ├── Teacher.php
│   │   │   │   ├── ClassRoom.php
│   │   │   │   ├── Subject.php
│   │   │   │   └── Schedule.php
│   │   │   ├── Routes/
│   │   │   ├── Views/
│   │   │   └── Database/migrations/
│   │   │
│   │   ├── Finance/
│   │   │   ├── Http/Controllers/
│   │   │   ├── Models/
│   │   │   │   ├── Invoice.php
│   │   │   │   ├── Payment.php
│   │   │   │   ├── Expense.php
│   │   │   │   └── BillType.php
│   │   │   ├── Routes/
│   │   │   ├── Views/
│   │   │   └── Database/migrations/
│   │   │
│   │   ├── PPDB/
│   │   │   ├── Http/Controllers/
│   │   │   ├── Models/
│   │   │   │   ├── Applicant.php
│   │   │   │   ├── Wave.php
│   │   │   │   └── FeeComponent.php
│   │   │   ├── Routes/
│   │   │   ├── Views/
│   │   │   └── Database/migrations/
│   │   │
│   │   ├── Assessment/
│   │   │   ├── Http/Controllers/
│   │   │   ├── Models/
│   │   │   │   ├── Grade.php
│   │   │   │   ├── GradeComponent.php
│   │   │   │   └── BehaviorGrade.php
│   │   │   ├── Routes/
│   │   │   └── Database/migrations/
│   │   │
│   │   └── Attendance/
│   │       ├── Http/Controllers/
│   │       ├── Models/
│   │       │   └── Attendance.php
│   │       ├── Routes/
│   │       └── Database/migrations/
│   │
│   ├── Services/
│   │   ├── Contracts/
│   │   │   └── UserServiceInterface.php
│   │   └── Implementations/
│   │       └── UserService.php
│   │
│   └── Providers/
│       ├── AppServiceProvider.php
│       └── ModuleServiceProvider.php
│
├── bootstrap/
├── config/
├── database/
├── modules.json (untuk autoload)
├── routes/
│   ├── web.php (main routes)
│   └── api.php (API routes)
├── resources/
│   └── views/
│       ├── components/
│       │   ├── ui/
│       │   │   ├── button.blade.php
│       │   │   ├── card.blade.php
│       │   │   ├── modal.blade.php
│       │   │   ├── table.blade.php
│       │   │   └── form/
│       │   │       ├── input.blade.php
│       │   │       ├── select.blade.php
│       │   │       └── textarea.blade.php
│       │   └── layout/
│       │       ├── sidebar.blade.php
│       │       ├── navbar.blade.php
│       │       └── footer.blade.php
│       └── layouts/
│           ├── app.blade.php
│           └── guest.blade.php
│
└── tests/
```

---

## 3. Tahapan Implementasi

### Phase 1: Core Foundation (Week 1-2)

#### 3.1.1 Create Base Classes
```
app/Core/Base/
├── BaseController.php
├── BaseModel.php  
└── BaseService.php
```

**BaseController.php:**
```php
<?php

namespace App\Core\Base;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

abstract class BaseController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    protected $service;

    public function __construct($service = null)
    {
        $this->service = $service;
    }

    protected function respond($data, $message = null, $status = 200)
    {
        return response()->json([
            'success' => in_array($status, [200, 201]),
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function respondError($message, $status = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}
```

**BaseModel.php:**
```php
<?php

namespace App\Core\Base;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    use \App\Core\Traits\HasUuid;
    use \App\Core\Traits\ScopeActive;
}
```

#### 3.1.2 Create Traits
```
app/Core/Traits/
├── HasUuid.php
├── ScopeActive.php
└── HasAuditLog.php
```

---

### Phase 2: Module Structure (Week 2-3)

#### 3.2.1 Setup Module System
Buat `modules.json`:
```json
{
    "modules": [
        "User",
        "Academic", 
        "Finance",
        "PPDB",
        "Assessment",
        "Attendance"
    ]
}
```

#### 3.2.2 Create ModuleServiceProvider
```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class ModuleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $modules = $this->getModules();
        
        foreach ($modules as $module) {
            $this->registerModule($module);
        }
    }

    public function boot()
    {
        $modules = $this->getModules();
        
        foreach ($modules as $module) {
            $this->bootModule($module);
        }
    }

    protected function getModules()
    {
        $modulesPath = base_path('app/Modules');
        
        if (!File::exists($modulesPath)) {
            return [];
        }

        return array_filter(File::directories($modulesPath), function($dir) {
            return File::exists($dir . '/Http/Controllers');
        });
    }

    protected function registerModule($module)
    {
        $name = basename($module);
        
        // Register routes
        if (File::exists($module . '/Routes/web.php')) {
            // Route registration handled in boot
        }

        // Register migrations
        if (File::exists($module . '/Database/migrations')) {
            $this->loadMigrationsFrom($module . '/Database/migrations');
        }
    }

    protected function bootModule($module)
    {
        $name = basename($module);
        
        // Load routes
        if (File::exists($module . '/Routes/web.php')) {
            $this->loadRoutesFrom($module . '/Routes/web.php');
        }

        // Load views
        if (File::exists($module . '/Views')) {
            $this->loadViewsFrom($module . '/Views', $name);
        }
    }
}
```

---

### Phase 3: Module Migration (Week 3-6)

#### 3.3.1 Module: User
```
app/Modules/User/
├── Http/
│   ├── Controllers/
│   │   ├── UserController.php
│   │   └── RoleController.php
│   └── Requests/
├── Models/
│   ├── User.php
│   └── Role.php
├── Routes/
│   └── web.php
├── Views/
│   ├── index.blade.php
│   └── form.blade.php
└── Database/
    └── migrations/
```

#### 3.3.2 Module: Academic
```
app/Modules/Academic/
├── Http/
│   ├── Controllers/
│   │   ├── StudentController.php
│   │   ├── TeacherController.php
│   │   ├── ClassRoomController.php
│   │   └── ScheduleController.php
│   └── Requests/
├── Models/
│   ├── Student.php
│   ├── Teacher.php
│   ├── ClassRoom.php
│   ├── Subject.php
│   ├── AcademicYear.php
│   └── Schedule.php
├── Routes/
│   └── web.php
├── Views/
│   ├── students/
│   ├── teachers/
│   └── classrooms/
└── Database/
    └── migrations/
```

#### 3.3.3 Module: Finance
```
app/Modules/Finance/
├── Http/
│   ├── Controllers/
│   │   ├── InvoiceController.php
│   │   ├── PaymentController.php
│   │   ├── ExpenseController.php
│   │   └── ReportController.php
│   └── Requests/
├── Models/
│   ├── Invoice.php
│   ├── Payment.php
│   ├── Expense.php
│   ├── BillType.php
│   ├── ExpenseCategory.php
│   ├── InstallmentPlan.php
│   └── CashTransaction.php
├── Routes/
│   └── web.php
├── Views/
│   ├── invoices/
│   ├── payments/
│   └── reports/
└── Database/
    └── migrations/
```

#### 3.3.4 Module: PPDB
```
app/Modules/PPDB/
├── Http/
│   ├── Controllers/
│   │   ├── PublicController.php
│   │   ├── ApplicantController.php
│   │   └── WaveController.php
│   └── Requests/
├── Models/
│   ├── Applicant.php
│   ├── Wave.php
│   └── FeeComponent.php
├── Routes/
│   └── web.php
├── Views/
│   ├── public/
│   └── admin/
└── Database/
    └── migrations/
```

#### 3.3.5 Module: Assessment
```
app/Modules/Assessment/
├── Http/
│   ├── Controllers/
│   │   ├── GradeController.php
│   │   └── BehaviorGradeController.php
│   └── Requests/
├── Models/
│   ├── Grade.php
│   ├── GradeComponent.php
│   └── BehaviorGrade.php
├── Routes/
│   └── web.php
├── Views/
│   ├── grades/
│   └── reports/
└── Database/
    └── migrations/
```

#### 3.3.6 Module: Attendance
```
app/Modules/Attendance/
├── Http/
│   ├── Controllers/
│   │   └── AttendanceController.php
│   └── Requests/
├── Models/
│   └── Attendance.php
├── Routes/
│   └── web.php
├── Views/
│   └── index.blade.php
└── Database/
    └── migrations/
```

---

### Phase 4: UI Components (Week 6-7)

#### 3.4.1 Create Blade Components
```
resources/views/components/
├── ui/
│   ├── button.blade.php
│   ├── card.blade.php
│   ├── modal.blade.php
│   ├── table.blade.php
│   ├── badge.blade.php
│   ├── alert.blade.php
│   └── form/
│       ├── input.blade.php
│       ├── select.blade.php
│       ├── textarea.blade.php
│       ├── checkbox.blade.php
│       └── file.blade.php
└── layout/
    ├── sidebar.blade.php
    ├── navbar.blade.php
    ├── footer.blade.php
    └── breadcrumbs.blade.php
```

**Contoh Button Component:**
```php
<?php

namespace App\View\Components\Ui;

use Illuminate\View\Component;

class Button extends Component
{
    public function __construct(
        public string $type = 'button',
        public string $variant = 'primary',
        public string $size = 'md',
        public bool $disabled = false,
        public string $href = '',
    ) {}

    public function render()
    {
        return view('components.ui.button');
    }
}
```

---

## 4. Migration Checklist

### Sebelum Migrasi:

1. [ ] Backup database
2. [ ] Export semua routes saat ini
3. [ ] Catat semua controller methods
4. [ ] Identifikasi semua relationships

### Selama Migrasi:

1. [ ] Phase 1: Core Foundation
   - [ ] Buat BaseController, BaseModel, BaseService
   - [ ] Buat Traits yang diperlukan

2. [ ] Phase 2: Module Structure  
   - [ ] Setup ModuleServiceProvider
   - [ ] Konfigurasi autoloading

3. [ ] Phase 3: Module Migration (satu per satu)
   - [ ] User Module
   - [ ] Academic Module
   - [ ] Finance Module
   - [ ] PPDB Module
   - [ ] Assessment Module
   - [ ] Attendance Module

4. [ ] Phase 4: UI Components
   - [ ] Buat reusable components
   - [ ] Update views untuk menggunakan components

### Setelah Migrasi:

1. [ ] Testing semua fitur
2. [ ] Verify routes berfungsi
3. [ ] Check semua relationships
4. [ ] Update dokumentasi

---

## 5. Rute Perubahan

### Sebelum (Saat Ini):
```php
// routes/tenant.php
Route::middleware(['auth'])->group(function () {
    Route::get('/students', [\App\Http\Controllers\Tenant\StudentController::class, 'index']);
    Route::get('/finance/invoices', [\App\Http\Controllers\Tenant\FinanceController::class, 'invoices']);
});
```

### Sesudah (Target):
```php
// app/Modules/Academic/Routes/web.php
Route::middleware(['auth'])->group(function () {
    Route::get('/students', [\App\Modules\Academic\Http\Controllers\StudentController::class, 'index']);
});

// app/Modules/Finance/Routes/web.php
Route::middleware(['auth'])->group(function () {
    Route::get('/invoices', [\App\Modules\Finance\Http\Controllers\InvoiceController::class, 'index']);
});
```

---

## 6. Estimated Timeline

| Phase | Description | Duration |
|-------|-------------|----------|
| 1 | Core Foundation | 1-2 minggu |
| 2 | Module Structure | 1 minggu |
| 3 | Module Migration | 3-4 minggu |
| 4 | UI Components | 1-2 minggu |

**Total: 6-9 minggu**

---

## 7. Risk Mitigation

| Risk | Impact | Mitigation |
|------|--------|------------|
| Route conflicts | High | Test setiap module terpisah |
| Database migration | High | Backup sebelum migrasi |
| Broken relationships | High | Test setiap relationship |
| Performance degradation | Medium | Optimize queries setelah migrasi |
| Missing features | Medium | Dokumentasi semua features |

---

## 8. Next Steps

1. **Konfirmasi Plan** - Apakah struktur ini sesuai dengan kebutuhan?
2. **Setup Development Environment** - Siapkan environment untuk refactoring
3. **Mulai Phase 1** - Implementasi Core Foundation

Apakah Anda ingin saya memulai implementasi Phase 1 (Core Foundation)?