<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('acquisition_requests', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->unsignedInteger('requested_by');
            $table->unsignedInteger('item_type_id')->nullable();
            $table->unsignedInteger('reviewed_by')->nullable();
            $table->unsignedInteger('closed_remarks')->nullable();

            // Bibliographic information
            $table->string('title');
            $table->string('author')->nullable();
            $table->string('isbn', 20)->nullable();
            $table->string('publisher')->nullable();
            $table->year('publication_year')->nullable();
            $table->string('edition')->nullable();

            // Request details
            $table->string('subject')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->text('justification')->nullable();

            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');

            // Financial / supplier information
            $table->decimal('estimated_unit_price', 12, 2)->nullable();
            $table->decimal('estimated_total_price', 12, 2)->nullable();
            $table->string('preferred_supplier')->nullable();

            // Workflow
            $table->enum('request_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('procurement_status', ['pending', 'ordered', 'received'])->nullable();
            $table->boolean('is_closed')->default(false);

            // Review
            $table->timestamp('reviewed_at')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acquisition_requests');
    }
};
