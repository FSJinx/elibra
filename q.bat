php artisan meilisearch:setup
php artisan scout:import "App\Models\CatalogIndex"
php artisan queue:work