<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * FAQs no longer use categories. Drop the foreign-key column and the
     * faq_categories table on environments that were migrated before these
     * were removed from the schema (e.g. production deployed via `migrate`,
     * which never re-runs the edited create migrations).
     *
     * Guarded with hasColumn/dropIfExists so this is a harmless no-op on fresh
     * databases where the column and table never existed.
     */
    public function up(): void
    {
        if (Schema::hasColumn('faqs', 'faq_category_id')) {
            Schema::table('faqs', function (Blueprint $table) {
                $table->dropForeign(['faq_category_id']);
                $table->dropColumn('faq_category_id');
            });
        }

        Schema::dropIfExists('faq_categories');
    }

    public function down(): void
    {
        // Categories are intentionally removed; this migration is not reversible.
    }
};
