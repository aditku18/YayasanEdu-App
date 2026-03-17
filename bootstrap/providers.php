<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\TenancyServiceProvider::class,
    App\Providers\ModuleServiceProvider::class,
    App\Providers\PaymentServiceProvider::class,
    App\Modules\CBT\Providers\CbtServiceProvider::class,
    App\Modules\Attendance\Providers\AttendanceServiceProvider::class,
];
