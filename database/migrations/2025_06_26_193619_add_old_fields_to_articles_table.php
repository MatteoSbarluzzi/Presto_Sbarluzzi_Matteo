<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('old_title')->nullable()->after('title');
            $table->text('old_description')->nullable()->after('description');
            $table->decimal('old_price', 8, 2)->nullable()->after('price');
            $table->unsignedBigInteger('old_category_id')->nullable()->after('category_id');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('old_title');
            $table->dropColumn('old_description');
            $table->dropColumn('old_price');
            $table->dropColumn('old_category_id');
        });
    }
};
