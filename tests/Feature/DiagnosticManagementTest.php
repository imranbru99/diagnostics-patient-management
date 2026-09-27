<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Patient;
use App\Models\Report;
use App\Models\ReportValue;
use App\Models\Sample;
use App\Models\Test;
use App\Models\TestCategory;
use App\Models\TestParameter;
use App\Models\TestReferenceRange;
use App\Services\AI\DiagnosticAIService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiagnosticManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function patient_registration_generates_unique_mrn_and_slug()
    {
        $branch = Branch::create([
            'name' => 'Main Lab',
            'code' => 'BR-01',
            'slug' => 'main-lab',
            'is_central' => true,
        ]);

        $patient = Patient::create([
            'branch_id' => $branch->id,
            'name' => 'Kamal Hossain',
            'phone' => '+880 1711-223344',
            'gender' => 'male',
            'age_years' => 45,
        ]);

        $this->assertNotEmpty($patient->mrn);
        $this->assertStringStartsWith('MRN-', $patient->mrn);
        $this->assertNotEmpty($patient->slug);
        $this->assertStringContainsString('kamal-hossain', $patient->slug);
    }

    /** @test */
    public function sample_barcode_lifecycle_transitions_and_logs_custody()
    {
        $branch = Branch::create(['name' => 'Main Lab', 'code' => 'BR-01', 'slug' => 'main-lab']);
        $patient = Patient::create(['branch_id' => $branch->id, 'name' => 'Farhana', 'phone' => '+880 1711-000000']);
        $cat = TestCategory::create(['name' => 'Hematology', 'code' => 'HEM', 'slug' => 'hematology']);
        $test = Test::create(['test_category_id' => $cat->id, 'code' => 'CBC', 'name' => 'CBC', 'slug' => 'cbc', 'price' => 30]);
        $order = Order::create(['patient_id' => $patient->id, 'branch_id' => $branch->id, 'order_date' => now(), 'total_amount' => 30]);
        $item = OrderItem::create(['order_id' => $order->id, 'test_id' => $test->id, 'price' => 30, 'final_price' => 30]);

        $sample = Sample::create([
            'order_item_id' => $item->id,
            'patient_id' => $patient->id,
            'branch_id' => $branch->id,
            'sample_type' => 'Blood',
            'status' => Sample::STATUS_COLLECTED,
        ]);

        $this->assertNotEmpty($sample->barcode);
        $this->assertEquals(Sample::STATUS_COLLECTED, $sample->status);

        // Transition: Collected -> Received at Lab
        $sample->transitionTo(Sample::STATUS_RECEIVED_AT_LAB, null, 'Received at accession desk');
        $this->assertEquals(Sample::STATUS_RECEIVED_AT_LAB, $sample->fresh()->status);
        $this->assertCount(1, $sample->custodyLogs);

        // Transition: In Testing
        $sample->transitionTo(Sample::STATUS_IN_TESTING, null, 'Placed on automated analyzer');
        $this->assertEquals(Sample::STATUS_IN_TESTING, $sample->fresh()->status);
        $this->assertCount(2, $sample->custodyLogs);
    }

    /** @test */
    public function abnormal_reference_interval_evaluates_correct_clinical_flags()
    {
        $cat = TestCategory::create(['name' => 'Biochemistry', 'code' => 'BIO', 'slug' => 'biochemistry']);
        $test = Test::create(['test_category_id' => $cat->id, 'code' => 'CREAT', 'name' => 'Creatinine', 'slug' => 'creatinine', 'price' => 20]);
        $param = TestParameter::create(['test_id' => $test->id, 'name' => 'Serum Creatinine', 'code' => 'CREAT', 'unit' => 'mg/dL']);

        $ref = TestReferenceRange::create([
            'test_parameter_id' => $param->id,
            'gender' => 'male',
            'min_value' => 0.7,
            'max_value' => 1.3,
            'critical_low' => 0.3,
            'critical_high' => 5.0,
        ]);

        $this->assertEquals('normal', $ref->evaluateFlag(1.0));
        $this->assertEquals('high', $ref->evaluateFlag(2.1));
        $this->assertEquals('critical_high', $ref->evaluateFlag(5.8));
        $this->assertEquals('low', $ref->evaluateFlag(0.5));
    }

    /** @test */
    public function ai_service_generates_plain_language_summary_and_flags_abnormals()
    {
        $branch = Branch::create(['name' => 'Main Lab', 'code' => 'BR-01', 'slug' => 'main-lab']);
        $patient = Patient::create(['branch_id' => $branch->id, 'name' => 'Nasir Khan', 'phone' => '+880 1711-123123', 'gender' => 'male', 'age_years' => 50]);
        $cat = TestCategory::create(['name' => 'Hematology', 'code' => 'HEM', 'slug' => 'hematology']);
        $test = Test::create(['test_category_id' => $cat->id, 'code' => 'CBC', 'name' => 'Complete Blood Count', 'slug' => 'cbc', 'price' => 30]);
        $param = TestParameter::create(['test_id' => $test->id, 'name' => 'Hemoglobin', 'code' => 'HGB', 'unit' => 'g/dL']);
        $order = Order::create(['patient_id' => $patient->id, 'branch_id' => $branch->id, 'order_date' => now(), 'total_amount' => 30]);
        $item = OrderItem::create(['order_id' => $order->id, 'test_id' => $test->id, 'price' => 30, 'final_price' => 30]);

        $report = Report::create([
            'order_id' => $order->id,
            'order_item_id' => $item->id,
            'patient_id' => $patient->id,
            'branch_id' => $branch->id,
            'status' => 'draft',
        ]);

        ReportValue::create([
            'report_id' => $report->id,
            'test_parameter_id' => $param->id,
            'value_numeric' => 11.2,
            'flag' => 'low',
            'reference_range_snapshot' => '13.0 - 17.5 g/dL',
            'unit' => 'g/dL',
        ]);

        $aiService = new DiagnosticAIService();
        $summary = $aiService->generatePatientSummary($report);
        $flags = $aiService->flagAbnormalValues($report);

        $this->assertNotEmpty($summary);
        $this->assertStringContainsString('Nasir Khan', $summary);
        $this->assertCount(1, $flags);
        $this->assertEquals('Hemoglobin', $flags[0]['parameter']);
        $this->assertEquals('low', $flags[0]['flag']);
    }

    /** @test */
    public function public_verification_endpoint_verifies_authentic_hash()
    {
        $branch = Branch::create(['name' => 'Main Lab', 'code' => 'BR-01', 'slug' => 'main-lab']);
        $patient = Patient::create(['branch_id' => $branch->id, 'name' => 'Sultana Begum', 'phone' => '+880 1711-999000']);
        $cat = TestCategory::create(['name' => 'Biochemistry', 'code' => 'BIO', 'slug' => 'biochemistry']);
        $test = Test::create(['test_category_id' => $cat->id, 'code' => 'LFT', 'name' => 'Liver Panel', 'slug' => 'lft', 'price' => 40]);
        $order = Order::create(['patient_id' => $patient->id, 'branch_id' => $branch->id, 'order_date' => now(), 'total_amount' => 40]);
        $item = OrderItem::create(['order_id' => $order->id, 'test_id' => $test->id, 'price' => 40, 'final_price' => 40]);

        $report = Report::create([
            'order_id' => $order->id,
            'order_item_id' => $item->id,
            'patient_id' => $patient->id,
            'branch_id' => $branch->id,
            'status' => 'published',
        ]);

        $response = $this->get('/verify-report/' . $report->verification_hash);
        $response->assertStatus(200);
        $response->assertSee('Authentic Diagnostic Report');
        $response->assertSee($report->report_number);
    }
}
