<?php

return [
    App\Providers\AdminServiceProvider::class,
    App\Providers\AppServiceProvider::class,
    App\Providers\CatalogServiceProvider::class,
    App\Providers\FortifyServiceProvider::class,
    App\Providers\KitchenServiceProvider::class,
    App\Providers\ModuleServiceProvider::class,
    App\Modules\Ordering\OrderingServiceProvider::class,
    App\Providers\OrderingServiceProvider::class,
    App\Providers\PaymentsServiceProvider::class,
    App\Providers\ReportingServiceProvider::class,
];