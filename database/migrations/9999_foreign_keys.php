<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        //  1. Organizational Entities
        [
            'name' => 'departments',
            'foreign_columns' => [
                ['name' => 'campus_id', 'references' => 'id', 'on' => 'campuses', 'onDelete' => 'restrict'],
            ],
        ],
        [
            'name' => 'libraries',
            'foreign_columns' => [
                ['name' => 'logo_id', 'references' => 'id', 'on' => 'media', 'onDelete' => 'set null'],
                ['name' => 'library_head_id', 'references' => 'id', 'on' => 'librarians', 'onDelete' => 'set null'],
                ['name' => 'campus_id', 'references' => 'id', 'on' => 'campuses', 'onDelete' => 'restrict'],
            ],
        ],
        [
            'name' => 'programs',
            'foreign_columns' => [
                ['name' => 'department_id', 'references' => 'id', 'on' => 'departments', 'onDelete' => 'restrict'],
            ],
        ],
        [
            'name' => 'sections',
            'foreign_columns' => [
                ['name' => 'librarian_id', 'references' => 'id', 'on' => 'librarians', 'onDelete' => 'set null'],
                ['name' => 'library_id', 'references' => 'id', 'on' => 'libraries', 'onDelete' => 'restrict'],
            ],
        ],

        //  2. Users, Accounts, and Permissions
        [
            'name' => 'librarians',
            'foreign_columns' => [
                ['name' => 'user_id', 'references' => 'id', 'on' => 'users', 'onDelete' => 'cascade'],
                ['name' => 'library_id', 'references' => 'id', 'on' => 'libraries', 'onDelete' => 'restrict'],
            ],
        ],
        [
            'name' => 'patrons',
            'foreign_columns' => [
                ['name' => 'user_id', 'references' => 'id', 'on' => 'users', 'onDelete' => 'cascade'],
                ['name' => 'program_id', 'references' => 'id', 'on' => 'programs', 'onDelete' => 'restrict'],
                ['name' => 'patron_type_id', 'references' => 'id', 'on' => 'patron_types', 'onDelete' => 'restrict'],
            ],
        ],
        [
            'name' => 'user_logs',
            'foreign_columns' => [
                ['name' => 'user_id', 'references' => 'id', 'on' => 'users', 'onDelete' => 'cascade'],
            ],
        ],
        [
            'name' => 'user_permissions',
            'foreign_columns' => [
                ['name' => 'user_id', 'references' => 'id', 'on' => 'users', 'onDelete' => 'cascade'],
                ['name' => 'permission_id', 'references' => 'id', 'on' => 'permissions', 'onDelete' => 'cascade'],
            ],
        ],
        [
            'name' => 'users',
            'foreign_columns' => [
                ['name' => 'profile_picture_id', 'references' => 'id', 'on' => 'media', 'onDelete' => 'set null'],
                ['name' => 'campus_id', 'references' => 'id', 'on' => 'campuses', 'onDelete' => 'restrict'],
            ],
        ],

        //  3. Catalog
        [
            'name' => 'aca_repos',
            'foreign_columns' => [
                ['name' => 'library_id', 'references' => 'id', 'on' => 'libraries', 'onDelete' => 'restrict'],
                ['name' => 'submitted_by', 'references' => 'id', 'on' => 'patrons', 'onDelete' => 'restrict'],
                ['name' => 'language_id', 'references' => 'id', 'on' => 'languages', 'onDelete' => 'set null'],
                ['name' => 'program_id', 'references' => 'id', 'on' => 'programs', 'onDelete' => 'set null'],
            ],
        ],
        [
            'name' => 'item_authors',
            'foreign_columns' => [
                ['name' => 'author_id', 'references' => 'id', 'on' => 'authors', 'onDelete' => 'cascade'],
                ['name' => 'item_id', 'references' => 'id', 'on' => 'items', 'onDelete' => 'cascade'],
                ['name' => 'authorship_id', 'references' => 'id', 'on' => 'authorships', 'onDelete' => 'restrict'],
            ],
        ],
        [
            'name' => 'item_publishers',
            'foreign_columns' => [
                ['name' => 'publisher_id', 'references' => 'id', 'on' => 'publishers', 'onDelete' => 'set null'],
                ['name' => 'item_id', 'references' => 'id', 'on' => 'items', 'onDelete' => 'cascade'],
            ],
        ],
        [
            'name' => 'items',
            'foreign_columns' => [
                ['name' => 'department_id', 'references' => 'id', 'on' => 'departments', 'onDelete' => 'set null'],

                ['name' => 'item_type_id', 'references' => 'id', 'on' => 'item_types', 'onDelete' => 'restrict'],
                ['name' => 'item_type_category_id', 'references' => 'id', 'on' => 'item_type_categories', 'onDelete' => 'restrict'],
                ['name' => 'language_id', 'references' => 'id', 'on' => 'languages', 'onDelete' => 'set null'],
                ['name' => 'library_id', 'references' => 'id', 'on' => 'libraries', 'onDelete' => 'restrict'],
                ['name' => 'cover_media_id', 'references' => 'id', 'on' => 'media', 'onDelete' => 'set null'],
            ],
        ],
        [
            'name' => 'inventories',
            'foreign_columns' => [
                ['name' => 'librarian_id', 'references' => 'id', 'on' => 'librarians', 'onDelete' => 'set null'],
                ['name' => 'section_id', 'references' => 'id', 'on' => 'sections', 'onDelete' => 'restrict'],
            ],
        ],
        [
            'name' => 'inventory__lines',
            'foreign_columns' => [
                ['name' => 'inventory_id', 'references' => 'id', 'on' => 'inventories', 'onDelete' => 'cascade'],
                ['name' => 'accession_id', 'references' => 'id', 'on' => 'accessions', 'onDelete' => 'cascade'],
                ['name' => 'verified_by', 'references' => 'id', 'on' => 'librarians', 'onDelete' => 'set null'],
            ],
        ],

        //  4. Standards
        [
            'name' => 'authorships',
            'foreign_columns' => [
                ['name' => 'item_type_id', 'references' => 'id', 'on' => 'item_types', 'onDelete' => 'cascade'],
            ],
        ],
        [
            'name' => 'item_type_categories',
            'foreign_columns' => [
                ['name' => 'item_type_id', 'references' => 'id', 'on' => 'item_types', 'onDelete' => 'cascade'],
            ],
        ],

        //  5. Authority Control

        //  6. Acquisitions and Accessions
        [
            'name' => 'accessions',
            'foreign_columns' => [
                ['name' => 'item_id', 'references' => 'id', 'on' => 'items', 'onDelete' => 'cascade'],
                ['name' => 'section_id', 'references' => 'id', 'on' => 'sections', 'onDelete' => 'set null'],
                ['name' => 'acquisition_line_id', 'references' => 'id', 'on' => 'acquisition_lines', 'onDelete' => 'set null'],
            ],
        ],
        [
            'name' => 'acquisition_lines',
            'foreign_columns' => [
                ['name' => 'item_id', 'references' => 'id', 'on' => 'items', 'onDelete' => 'restrict'],
                ['name' => 'acquisition_id', 'references' => 'id', 'on' => 'acquisitions', 'onDelete' => 'cascade'],
            ],
        ],
        [
            'name' => 'acquisition_requests',
            'foreign_columns' => [
                ['name' => 'requested_by', 'references' => 'id', 'on' => 'users', 'onDelete' => 'set null'],
                ['name' => 'item_type_id', 'references' => 'id', 'on' => 'item_types', 'onDelete' => 'set null'],
                ['name' => 'reviewed_by', 'references' => 'id', 'on' => 'librarians', 'onDelete' => 'set null'],
            ],
        ],
        [
            'name' => 'acquisitions',
            'foreign_columns' => [
                ['name' => 'receiver_id', 'references' => 'id', 'on' => 'librarians', 'onDelete' => 'set null'],
                ['name' => 'acquisition_request_id', 'references' => 'id', 'on' => 'acquisition_requests', 'onDelete' => 'restrict'],
            ],
        ],

        //  7. Circulations
        [
            'name' => 'attendance_logs',
            'foreign_columns' => [
                ['name' => 'patron_id', 'references' => 'id', 'on' => 'users', 'onDelete' => 'set null'],
                ['name' => 'library_id', 'references' => 'id', 'on' => 'libraries', 'onDelete' => 'restrict'],
                ['name' => 'section_id', 'references' => 'id', 'on' => 'sections', 'onDelete' => 'restrict'],
            ],
        ],
        [
            'name' => 'circulations',
            'foreign_columns' => [
                ['name' => 'processed_by', 'references' => 'id', 'on' => 'librarians', 'onDelete' => 'set null'],
                ['name' => 'accession_id', 'references' => 'id', 'on' => 'accessions', 'onDelete' => 'no action'],
                ['name' => 'patron_id', 'references' => 'id', 'on' => 'patrons', 'onDelete' => 'set null'],
                ['name' => 'loan_mode_id', 'references' => 'id', 'on' => 'loan_modes', 'onDelete' => 'restrict'],
                ['name' => 'return_received_by', 'references' => 'id', 'on' => 'librarians', 'onDelete' => 'set null'],
            ],
        ],
        [
            'name' => 'fines_transactions',
            'foreign_columns' => [
                ['name' => 'patron_id', 'references' => 'id', 'on' => 'patrons', 'onDelete' => 'restrict'],
                ['name' => 'circulation_id', 'references' => 'id', 'on' => 'circulations', 'onDelete' => 'cascade'],
                ['name' => 'processed_by', 'references' => 'id', 'on' => 'librarians', 'onDelete' => 'set null'],
            ],
        ],
        [
            'name' => 'holdings',
            'foreign_columns' => [
                ['name' => 'reserved_by', 'references' => 'id', 'on' => 'patrons', 'onDelete' => 'cascade'],
                ['name' => 'accession_id', 'references' => 'id', 'on' => 'accessions', 'onDelete' => 'cascade'],
                ['name' => 'processed_by', 'references' => 'id', 'on' => 'librarians', 'onDelete' => 'set null'],
            ],
        ],
        [
            'name' => 'patron_type_loan_policies',
            'foreign_columns' => [
                ['name' => 'patron_type_id', 'references' => 'id', 'on' => 'patron_types', 'onDelete' => 'cascade'],
                ['name' => 'loan_mode_id', 'references' => 'id', 'on' => 'loan_modes', 'onDelete' => 'cascade'],
            ],
        ],

        //  8. Serial Management
        [
            'name' => 'subscription_credentials',
            'foreign_columns' => [
                ['name' => 'subscription_id', 'references' => 'id', 'on' => 'subscriptions', 'onDelete' => 'cascade'],
                ['name' => 'campus_id', 'references' => 'id', 'on' => 'campuses', 'onDelete' => 'cascade'],
            ],
        ],
        [
            'name' => 'subscriptions',
            'foreign_columns' => [
                ['name' => 'thumbnail_id', 'references' => 'id', 'on' => 'media', 'onDelete' => 'cascade'],
            ],
        ],
        [
            'name' => 'catalog_indices',
            'foreign_columns' => [
                ['name' => 'item_id', 'references' => 'id', 'on' => 'items', 'onDelete' => 'cascade'],
                ['name' => 'campus_id', 'references' => 'id', 'on' => 'campuses', 'onDelete' => 'restrict'],
                ['name' => 'library_id', 'references' => 'id', 'on' => 'libraries', 'onDelete' => 'restrict'],

                ['name' => 'item_type_id', 'references' => 'id', 'on' => 'item_types', 'onDelete' => 'set null'],
                ['name' => 'item_type_category_id', 'references' => 'id', 'on' => 'item_type_categories', 'onDelete' => 'set null'],
                ['name' => 'department_id', 'references' => 'id', 'on' => 'departments', 'onDelete' => 'set null'],
            ],
        ],

        // 9. Media
        [
            'name' => 'media',
            'foreign_columns' => [
                // ['name' => 'item_id', 'references' => 'id', 'on' => 'items', 'onDelete' => 'cascade'],
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
