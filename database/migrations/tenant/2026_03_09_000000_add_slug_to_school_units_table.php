<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('school_units', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });

        // Auto-generate slugs for existing school units
        $schools = \DB::table('school_units')->get();
        foreach ($schools as $school) {
            $slug = Str::slug($school->name);
            // Handle duplicate slugs by appending a number
            $originalSlug = $slug;
            $counter = 1;
            while (\DB::table('school_units')->where('slug', $slug)->where('id', '!=', $school->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            \DB::table('school_units')->where('id', $school->id)->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_units', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
