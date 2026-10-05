<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSessionFieldsToAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('class_session_id')->nullable()->after('intake_subject_id');
            // Semantic value of the row. `status` (0/1) stays the row-active flag.
            $table->enum('attendance_status', ['present', 'absent', 'late', 'excused'])
                ->default('present')->after('status');
            $table->time('left_early_at')->nullable()->after('attendance_status');
            $table->text('remarks')->nullable()->after('left_early_at');          // internal (trainer/admin)
            $table->text('feedback')->nullable()->after('remarks');               // shown to student when visible
            $table->boolean('feedback_visible_to_student')->default(false)->after('feedback');
            // Provenance, so a self/QR/online row is distinguishable from a marked one later.
            $table->enum('marked_via', ['trainer', 'admin', 'qr', 'pin', 'self', 'online_class'])
                ->nullable()->after('feedback_visible_to_student');
            $table->index('class_session_id');
        });

        // Every existing row means "present" (absence was previously the absence of a row).
        // attendance_status already defaults to 'present'; set provenance from user_type.
        DB::table('attendances')->where('user_type', 'Trainer')->update(['marked_via' => 'trainer']);
        DB::table('attendances')->where('user_type', 'Admin')->update(['marked_via' => 'admin']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex(['class_session_id']);
            $table->dropColumn([
                'class_session_id',
                'attendance_status',
                'left_early_at',
                'remarks',
                'feedback',
                'feedback_visible_to_student',
                'marked_via',
            ]);
        });
    }
}
