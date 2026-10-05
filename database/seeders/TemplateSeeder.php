<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Template\Entities\Template;
use Modules\Template\Entities\TemplateData;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Schema::disableForeignKeyConstraints();
        DB::table('templates')->truncate();
        DB::table('template_data')->truncate();
        Schema::enableForeignKeyConstraints();

        $progression = Template::create([
            'name' => 'Progression Letter',
            'content' => ''
        ]);
        TemplateData::create(['template_id' => $progression->id, 'key' => 'date']);
        TemplateData::create(['template_id' => $progression->id, 'key' => 'completion_percent']);
        TemplateData::create(['template_id' => $progression->id, 'key' => 'content', 'value' => 'This is to confirm that <strong> {{ $student_name }} </strong> has completed {{ $completion_percent }} of the course required']);
        TemplateData::create(['template_id' => $progression->id, 'key' => 'logo']);
        TemplateData::create(['template_id' => $progression->id, 'key' => 'regards_signature']);
        TemplateData::create(['template_id' => $progression->id, 'key' => 'regards_name']);
        TemplateData::create(['template_id' => $progression->id, 'key' => 'regards_position']);
        TemplateData::create(['template_id' => $progression->id, 'key' => 'regards_college_name']);
        TemplateData::create(['template_id' => $progression->id, 'key' => 'regards_email']);
        TemplateData::create(['template_id' => $progression->id, 'key' => 'regards_phone']);
        TemplateData::create(['template_id' => $progression->id, 'key' => 'footer']);

        $enrollment = Template::create([
            'name' => 'Letter of Enrollment',
            'content' => ''
        ]);
        TemplateData::create(['template_id' => $enrollment->id, 'key' => 'date']);
        TemplateData::create(['template_id' => $enrollment->id, 'key' => 'content', 'value' => 'This letter of statement is valid for 28 days from the date of issue.']);
        TemplateData::create(['template_id' => $enrollment->id, 'key' => 'logo']);
        TemplateData::create(['template_id' => $enrollment->id, 'key' => 'regards_signature']);
        TemplateData::create(['template_id' => $enrollment->id, 'key' => 'regards_name']);
        TemplateData::create(['template_id' => $enrollment->id, 'key' => 'regards_position']);
        TemplateData::create(['template_id' => $enrollment->id, 'key' => 'regards_college_name']);
        TemplateData::create(['template_id' => $enrollment->id, 'key' => 'regards_email']);
        TemplateData::create(['template_id' => $enrollment->id, 'key' => 'regards_phone']);
        TemplateData::create(['template_id' => $enrollment->id, 'key' => 'footer']);

        $completion = Template::create([
            'name' => 'Completion Letter',
            'content' => ''
        ]);
        TemplateData::create(['template_id' => $completion->id, 'key' => 'date']);
        TemplateData::create(['template_id' => $completion->id, 'key' => 'logo']);
        TemplateData::create(['template_id' => $completion->id, 'key' => 'regards_signature']);
        TemplateData::create(['template_id' => $completion->id, 'key' => 'regards_name']);
        TemplateData::create(['template_id' => $completion->id, 'key' => 'regards_position']);
        TemplateData::create(['template_id' => $completion->id, 'key' => 'regards_college_name']);
        TemplateData::create(['template_id' => $completion->id, 'key' => 'regards_email']);
        TemplateData::create(['template_id' => $completion->id, 'key' => 'regards_phone']);
        TemplateData::create(['template_id' => $completion->id, 'key' => 'footer']);

        $term_break = Template::create([
            'name' => 'Term Break Letter',
            'content' => ''
        ]);
        TemplateData::create(['template_id' => $term_break->id, 'key' => 'date']);
        TemplateData::create(['template_id' => $term_break->id, 'key' => 'term_break_from']);
        TemplateData::create(['template_id' => $term_break->id, 'key' => 'term_break_to']);
        TemplateData::create(['template_id' => $term_break->id, 'key' => 'logo']);
        TemplateData::create(['template_id' => $term_break->id, 'key' => 'regards_signature']);
        TemplateData::create(['template_id' => $term_break->id, 'key' => 'regards_name']);
        TemplateData::create(['template_id' => $term_break->id, 'key' => 'regards_position']);
        TemplateData::create(['template_id' => $term_break->id, 'key' => 'regards_college_name']);
        TemplateData::create(['template_id' => $term_break->id, 'key' => 'regards_email']);
        TemplateData::create(['template_id' => $term_break->id, 'key' => 'regards_phone']);
        TemplateData::create(['template_id' => $term_break->id, 'key' => 'footer']);

        $vp_request = Template::create([
            'name' => 'VP Request Letter',
            'content' => ''
        ]);
        TemplateData::create(['template_id' => $vp_request->id, 'key' => 'date']);
        TemplateData::create(['template_id' => $vp_request->id, 'key' => 'work_hours']);
        TemplateData::create(['template_id' => $vp_request->id, 'key' => 'content', 'value' => 'As part of the qualification, students are required to complete <strong> 360 hours </strong> of work placement in an operational commercial kitchen. This is to be undertaken for a minimum of <strong>6 hours </strong> per day and <strong>6 days </strong> per week over <strong>10 weeks</strong>']);
        TemplateData::create(['template_id' => $vp_request->id, 'key' => 'logo']);
        TemplateData::create(['template_id' => $vp_request->id, 'key' => 'regards_signature']);
        TemplateData::create(['template_id' => $vp_request->id, 'key' => 'regards_name']);
        TemplateData::create(['template_id' => $vp_request->id, 'key' => 'regards_position']);
        TemplateData::create(['template_id' => $vp_request->id, 'key' => 'regards_college_name']);
        TemplateData::create(['template_id' => $vp_request->id, 'key' => 'regards_email']);
        TemplateData::create(['template_id' => $vp_request->id, 'key' => 'regards_phone']);
        TemplateData::create(['template_id' => $vp_request->id, 'key' => 'footer']);

        $leave_approval = Template::create([
            'name' => 'Leave Approval Letter',
            'content' => ''
        ]);
        TemplateData::create(['template_id' => $leave_approval->id, 'key' => 'date']);
        TemplateData::create(['template_id' => $leave_approval->id, 'key' => 'leave_from']);
        TemplateData::create(['template_id' => $leave_approval->id, 'key' => 'leave_to']);
        TemplateData::create(['template_id' => $leave_approval->id, 'key' => 'logo']);
        TemplateData::create(['template_id' => $leave_approval->id, 'key' => 'regards_signature']);
        TemplateData::create(['template_id' => $leave_approval->id, 'key' => 'regards_name']);
        TemplateData::create(['template_id' => $leave_approval->id, 'key' => 'regards_position']);
        TemplateData::create(['template_id' => $leave_approval->id, 'key' => 'regards_college_name']);
        TemplateData::create(['template_id' => $leave_approval->id, 'key' => 'regards_email']);
        TemplateData::create(['template_id' => $leave_approval->id, 'key' => 'regards_phone']);
        TemplateData::create(['template_id' => $leave_approval->id, 'key' => 'footer']);

        $vp = Template::create([
            'name' => 'VP Letter',
            'content' => ''
        ]);
        TemplateData::create(['template_id' => $vp->id, 'key' => 'date']);
        TemplateData::create(['template_id' => $vp->id, 'key' => 'required_work_placement_hours']);
        TemplateData::create(['template_id' => $vp->id, 'key' => 'certificate_issue_date']);
        TemplateData::create(['template_id' => $vp->id, 'key' => 'logo']);
        TemplateData::create(['template_id' => $vp->id, 'key' => 'regards_signature']);
        TemplateData::create(['template_id' => $vp->id, 'key' => 'regards_name']);
        TemplateData::create(['template_id' => $vp->id, 'key' => 'regards_position']);
        TemplateData::create(['template_id' => $vp->id, 'key' => 'regards_college_name']);
        TemplateData::create(['template_id' => $vp->id, 'key' => 'regards_email']);
        TemplateData::create(['template_id' => $vp->id, 'key' => 'regards_phone']);
        TemplateData::create(['template_id' => $vp->id, 'key' => 'footer']);

        $statement_of_receipt = Template::create([
            'name' => 'Statement of Receipt',
            'content' => ''
        ]);

        TemplateData::create(['template_id' => $statement_of_receipt->id, 'key' => 'date']);
        TemplateData::create(['template_id' => $statement_of_receipt->id, 'key' => 'receipt_no']);
        TemplateData::create(['template_id' => $statement_of_receipt->id, 'key' => 'account_name']);
        TemplateData::create(['template_id' => $statement_of_receipt->id, 'key' => 'bsb']);
        TemplateData::create(['template_id' => $statement_of_receipt->id, 'key' => 'bank_name']);
        TemplateData::create(['template_id' => $statement_of_receipt->id, 'key' => 'account_number']);
        TemplateData::create(['template_id' => $statement_of_receipt->id, 'key' => 'logo']);
        TemplateData::create(['template_id' => $statement_of_receipt->id, 'key' => 'regards_signature']);
        TemplateData::create(['template_id' => $statement_of_receipt->id, 'key' => 'regards_name']);
        TemplateData::create(['template_id' => $statement_of_receipt->id, 'key' => 'regards_position']);
        TemplateData::create(['template_id' => $statement_of_receipt->id, 'key' => 'regards_college_name']);
        TemplateData::create(['template_id' => $statement_of_receipt->id, 'key' => 'regards_email']);
        TemplateData::create(['template_id' => $statement_of_receipt->id, 'key' => 'regards_phone']);
        TemplateData::create(['template_id' => $statement_of_receipt->id, 'key' => 'footer']);
    }
}
