<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        [
            'name' => 'item_type_categories',
            'foreign_columns' => [
                ['name' => 'item_type_id', 'references' => 'id', 'on' => 'item_types', 'onDelete' => 'cascade'],
            ],
        ],
        [
            'name' => 'branches',
            'foreign_columns' => [
                ['name' => 'logo_id', 'references' => 'id', 'on' => 'media', 'onDelete' => 'cascade'],
                ['name' => 'branch_head_id', 'references' => 'id', 'on' => 'librarians', 'onDelete' => 'cascade'],
                ['name' => 'campus_id', 'references' => 'id', 'on' => 'campuses', 'onDelete' => 'cascade'],
            ],
        ],
        [
            'name' => 'branch_sections',
            'foreign_columns' => [
                ['name' => 'section_id', 'references' => 'id', 'on' => 'sections', 'onDelete' => 'cascade'],
                ['name' => 'branch_id', 'references' => 'id', 'on' => 'branches', 'onDelete' => 'cascade'],
            ],
            'unique_columns' => [
                ['branch_id', 'section_id'],
            ],
        ],
        [
            'name' => 'departments',
            'foreign_columns' => [
                ['name' => 'campus_id', 'references' => 'id', 'on' => 'campuses', 'onDelete' => 'cascade'],
            ],
        ],
        [
            'name' => 'programs',
            'foreign_columns' => [
                ['name' => 'department_id', 'references' => 'id', 'on' => 'departments', 'onDelete' => 'cascade'],
            ],
        ],
        [
            'name' => 'users',
            'foreign_columns' => [
                ['name' => 'profile_picture_id', 'references' => 'id', 'on' => 'media', 'onDelete' => 'cascade'],
                ['name' => 'campus_id', 'references' => 'id', 'on' => 'campuses', 'onDelete' => 'cascade'],
            ],
        ],
        [
            'name' => 'librarians',
            'foreign_columns' => [
                ['name' => 'user_id', 'references' => 'id', 'on' => 'users', 'onDelete' => 'cascade'],
                ['name' => 'branch_id', 'references' => 'id', 'on' => 'branches', 'onDelete' => 'cascade'],
            ],
        ],
        [
            'name' => 'patrons',
            'foreign_columns' => [
                ['name' => 'user_id', 'references' => 'id', 'on' => 'users', 'onDelete' => 'cascade'],
                ['name' => 'program_id', 'references' => 'id', 'on' => 'programs', 'onDelete' => 'cascade'],
                ['name' => 'patron_type_id', 'references' => 'id', 'on' => 'patron_types', 'onDelete' => 'cascade'],
            ],
        ],
        [
            'name' => 'subscriptions',
            'foreign_columns' => [
                ['name' => 'thumbnail_id', 'references' => 'id', 'on' => 'media', 'onDelete' => 'cascade'],
            ],
        ],
        [
            'name' => 'subscription_credentials',
            'foreign_columns' => [
                ['name' => 'subscription_id', 'references' => 'id', 'on' => 'subscriptions', 'onDelete' => 'cascade'],
                ['name' => 'campus_id', 'references' => 'id', 'on' => 'campuses', 'onDelete' => 'cascade'],
            ],
        ],
        [
            'name' => 'items',
            'foreign_columns' => [
                ['name' => 'language_id', 'references' => 'id', 'on' => 'languages', 'onDelete' => 'cascade'],
                ['name' => 'branch_id', 'references' => 'id', 'on' => 'branches', 'onDelete' => 'cascade'],
                ['name' => 'item_type_id', 'references' => 'id', 'on' => 'item_types', 'onDelete' => 'cascade'],
                ['name' => 'item_type_category_id', 'references' => 'id', 'on' => 'item_type_categories', 'onDelete' => 'cascade'],
            ],
        ],
        [
            'name' => 'academics',
            'foreign_columns' => [
                ['name' => 'item_id', 'references' => 'id', 'on' => 'items', 'onDelete' => 'cascade'],
                ['name' => 'department_id', 'references' => 'id', 'on' => 'departments', 'onDelete' => 'cascade'],
            ],
        ],
        [
            'name' => 'serials',
            'foreign_columns' => [
                ['name' => 'item_id', 'references' => 'id', 'on' => 'items', 'onDelete' => 'cascade'],
            ],
        ],
        [
            'name' => 'catalog_indices',
            'foreign_columns' => [
                ['name' => 'item_id', 'references' => 'id', 'on' => 'items', 'onDelete' => 'cascade'],
                ['name' => 'branch_id', 'references' => 'id', 'on' => 'branches', 'onDelete' => 'cascade'],
                ['name' => 'item_type_id', 'references' => 'id', 'on' => 'item_types', 'onDelete' => 'cascade'],
                ['name' => 'item_type_category_id', 'references' => 'id', 'on' => 'item_type_categories', 'onDelete' => 'cascade'],
                ['name' => 'department_id', 'references' => 'id', 'on' => 'departments', 'onDelete' => 'cascade'],
            ],
        ],
        // [
        //     'name' => 'catalog_embeddings',
        //     'foreign_columns' => [
        //         ['name' => 'catalog_index_id', 'references' => 'id', 'on' => 'catalog_indices', 'onDelete' => 'cascade'],
        //     ],   
        // ],
        [
            'name' => 'user_permissions',
            'foreign_columns' => [
                ['name' => 'user_id', 'references' => 'id', 'on' => 'users', 'onDelete' => 'cascade'],
                ['name' => 'permission_id', 'references' => 'id', 'on' => 'permissions', 'onDelete' => 'cascade'],
            ],
        ],
        [
            'name' => 'librarian_sections',
            'foreign_columns' => [
                ['name' => 'librarian_id', 'references' => 'id', 'on' => 'librarians', 'onDelete' => 'cascade'],
                ['name' => 'branch_section_id', 'references' => 'id', 'on' => 'branch_sections', 'onDelete' => 'cascade'],
            ],
        ],
        [
            'name' => 'item_authors',
            'foreign_columns' => [
                ['name' => 'author_id', 'references' => 'id', 'on' => 'authors', 'onDelete' => 'cascade'],
                ['name' => 'item_id', 'references' => 'id', 'on' => 'items', 'onDelete' => 'cascade'],
            ],
        ],
    ];

    // ============= LINKS RELATIONSHIPS ===============
    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table['name'], function (Blueprint $t) use ($table) {
                if (! empty($table['foreign_columns'])) {
                    foreach ($table['foreign_columns'] as $column) {
                        $t->foreign($column['name'])
                            ->references($column['references'])
                            ->on($column['on'])
                            ->onDelete($column['onDelete']);
                    }
                }

                if (! empty($table['unique_columns'])) {
                    foreach ($table['unique_columns'] as $column) {
                        $t->unique($column);
                    }
                }
            });
        }
    }

    // =========== REMOVES THE RELATIONSHIPS =============
    public function down(): void
    {
        foreach (array_reverse($this->tables) as $table) {
            Schema::table($table['name'], function (Blueprint $t) use ($table) {
                if (! empty($table['foreign_columns'])) {
                    foreach ($table['foreign_columns'] as $column) {
                        $t->dropForeign([$column['name']]);
                    }
                }

                if (! empty($table['unique_columns'])) {
                    foreach ($table['unique_columns'] as $column) {
                        $t->dropUnique($column);
                    }
                }
            });
        }
    }
};
