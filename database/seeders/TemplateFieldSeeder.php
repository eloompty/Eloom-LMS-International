<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Template\Entities\Template;
use Modules\Template\Entities\TemplateData;

class TemplateFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $progression = Template::where('name', 'Progression Letter')->first();
        TemplateData::create(['template_id' => $progression->id, 'key' => 'regards_email']);
        TemplateData::create(['template_id' => $progression->id, 'key' => 'regards_phone']);

        $enrollment = Template::where('name', 'Letter of Enrollment')->first();
        TemplateData::create(['template_id' => $enrollment->id, 'key' => 'regards_email']);
        TemplateData::create(['template_id' => $enrollment->id, 'key' => 'regards_phone']);

        $completion = Template::where('name', 'Completion Letter')->first();
        TemplateData::create(['template_id' => $completion->id, 'key' => 'regards_email']);
        TemplateData::create(['template_id' => $completion->id, 'key' => 'regards_phone']);

        $term_break = Template::where('name', 'Term Break Letter')->first();
        TemplateData::create(['template_id' => $term_break->id, 'key' => 'regards_email']);
        TemplateData::create(['template_id' => $term_break->id, 'key' => 'regards_phone']);

        $vp_request = Template::where('name', 'VP Request Letter')->first();
        TemplateData::create(['template_id' => $vp_request->id, 'key' => 'regards_email']);
        TemplateData::create(['template_id' => $vp_request->id, 'key' => 'regards_phone']);

        $leave_approval = Template::where('name', 'Leave Approval Letter')->first();
        TemplateData::create(['template_id' => $leave_approval->id, 'key' => 'regards_email']);
        TemplateData::create(['template_id' => $leave_approval->id, 'key' => 'regards_phone']);

        $vp = Template::where('name', 'VP Letter')->first();
        TemplateData::create(['template_id' => $vp->id, 'key' => 'regards_email']);
        TemplateData::create(['template_id' => $vp->id, 'key' => 'regards_phone']);

        $statement_of_receipt = Template::where('name', 'Statement of Receipt')->first();
        TemplateData::create(['template_id' => $statement_of_receipt->id, 'key' => 'regards_email']);
        TemplateData::create(['template_id' => $statement_of_receipt->id, 'key' => 'regards_phone']);
    }
}
