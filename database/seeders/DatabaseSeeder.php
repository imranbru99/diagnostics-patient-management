<?php

namespace Database\Seeders;

use App\Models\AiLog;
use App\Models\Appointment;
use App\Models\AttendanceLog;
use App\Models\Branch;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Patient;
use App\Models\PatientFamily;
use App\Models\Reagent;
use App\Models\Report;
use App\Models\ReportValue;
use App\Models\Sample;
use App\Models\SampleCustodyLog;
use App\Models\StaffShift;
use App\Models\Test;
use App\Models\TestCategory;
use App\Models\TestPackage;
use App\Models\TestParameter;
use App\Models\TestReferenceRange;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Branches
        $centralBranch = Branch::create([
            'name' => 'Central Molecular Reference Lab',
            'code' => 'BR-CENTRAL-01',
            'slug' => 'central-reference-lab',
            'phone' => '+880 1700-000001',
            'email' => 'central@apexdiagnostics.test',
            'address' => 'Plot 42, Health Avenue, Medical City, Dhaka 1212',
            'is_central' => true,
            'is_active' => true,
        ]);

        $downtownBranch = Branch::create([
            'name' => 'Downtown Collection & Imaging Hub',
            'code' => 'BR-DOWNTOWN-02',
            'slug' => 'downtown-imaging-hub',
            'phone' => '+880 1700-000002',
            'email' => 'downtown@apexdiagnostics.test',
            'address' => 'Tower 8, Commercial Road, Motijheel, Dhaka',
            'is_central' => false,
            'is_active' => true,
        ]);

        $northBranch = Branch::create([
            'name' => 'Northside Satellite Clinic & Sample Point',
            'code' => 'BR-NORTH-03',
            'slug' => 'northside-satellite',
            'phone' => '+880 1700-000003',
            'email' => 'northside@apexdiagnostics.test',
            'address' => 'Road 11, Block D, Uttara, Dhaka',
            'is_central' => false,
            'is_active' => true,
        ]);

        // 2. Staff & Clinical Users
        $admin = User::create([
            'name' => 'Dr. Imran Hossain',
            'email' => 'admin@apexdiagnostics.test',
            'phone' => '+880 1711-000000',
            'role' => 'admin',
            'designation' => 'Managing Director & Chief of Pathology',
            'password' => Hash::make('password'),
            'branch_id' => $centralBranch->id,
            'is_active' => true,
        ]);

        $pathologist = User::create([
            'name' => 'Prof. Dr. Nasreen Sultana',
            'email' => 'nasreen@apexdiagnostics.test',
            'phone' => '+880 1712-000000',
            'role' => 'pathologist',
            'designation' => 'Consultant Pathologist, MBBS, FCPS (Hematology)',
            'password' => Hash::make('password'),
            'branch_id' => $centralBranch->id,
            'is_active' => true,
        ]);

        $radiologist = User::create([
            'name' => 'Dr. Tariq Rahman',
            'email' => 'tariq@apexdiagnostics.test',
            'phone' => '+880 1713-000000',
            'role' => 'radiologist',
            'designation' => 'Consultant Radiologist, MBBS, MD (Radiology)',
            'password' => Hash::make('password'),
            'branch_id' => $downtownBranch->id,
            'is_active' => true,
        ]);

        $technologist = User::create([
            'name' => 'Kawsar Ahmed',
            'email' => 'kawsar@apexdiagnostics.test',
            'phone' => '+880 1714-000000',
            'role' => 'lab_technician',
            'designation' => 'Senior Medical Laboratory Technologist',
            'password' => Hash::make('password'),
            'branch_id' => $centralBranch->id,
            'is_active' => true,
        ]);

        $receptionist = User::create([
            'name' => 'Shamima Akter',
            'email' => 'reception@apexdiagnostics.test',
            'phone' => '+880 1715-000000',
            'role' => 'receptionist',
            'designation' => 'Front Desk Executive & Patient Liaison',
            'password' => Hash::make('password'),
            'branch_id' => $centralBranch->id,
            'is_active' => true,
        ]);

        // 3. Shifts & Attendance Setup
        $morningShift = StaffShift::create([
            'branch_id' => $centralBranch->id,
            'name' => 'Morning Shift (Phlebotomy & Core Lab)',
            'start_time' => '07:30:00',
            'end_time' => '15:30:00',
            'grace_period_minutes' => 15,
            'is_active' => true,
        ]);

        $eveningShift = StaffShift::create([
            'branch_id' => $centralBranch->id,
            'name' => 'Evening Shift (Routine & STAT)',
            'start_time' => '14:30:00',
            'end_time' => '22:30:00',
            'grace_period_minutes' => 15,
            'is_active' => true,
        ]);

        AttendanceLog::create([
            'user_id' => $technologist->id,
            'branch_id' => $centralBranch->id,
            'staff_shift_id' => $morningShift->id,
            'attendance_date' => today(),
            'check_in' => now()->startOfDay()->addHours(7)->addMinutes(25),
            'status' => 'present',
            'device_uid' => 'ZK-TECO-ADMS-01',
            'sync_source' => 'biometric_adms',
        ]);

        AttendanceLog::create([
            'user_id' => $receptionist->id,
            'branch_id' => $centralBranch->id,
            'staff_shift_id' => $morningShift->id,
            'attendance_date' => today(),
            'check_in' => now()->startOfDay()->addHours(7)->addMinutes(48),
            'status' => 'late',
            'late_minutes' => 18,
            'device_uid' => 'ZK-TECO-ADMS-01',
            'sync_source' => 'biometric_adms',
        ]);

        // 4. Referring Doctors
        $doc1 = Doctor::create([
            'code' => 'DOC-001',
            'name' => 'Prof. Dr. A. K. Azad',
            'designation' => 'MBBS, FCPS (Internal Medicine), MD (Cardiology)',
            'department' => 'Cardiology',
            'hospital_affiliation' => 'National Institute of Cardiovascular Diseases',
            'phone' => '+880 1819-111111',
            'email' => 'azad.cardio@health.test',
            'default_commission_rate' => 15.00,
            'payout_method' => 'Bank Transfer',
            'payout_details' => 'City Bank A/C: 1102938475, Motijheel Branch',
            'is_active' => true,
        ]);

        $doc2 = Doctor::create([
            'code' => 'DOC-002',
            'name' => 'Dr. Fahmida Shireen',
            'designation' => 'MBBS, M.Phil (Endocrinology)',
            'department' => 'Diabetology & Endocrinology',
            'hospital_affiliation' => 'BIRDEM General Hospital',
            'phone' => '+880 1819-222222',
            'email' => 'fahmida.endocrine@health.test',
            'default_commission_rate' => 12.00,
            'payout_method' => 'bKash',
            'payout_details' => 'bKash Merchant Wallet: 01819-222222',
            'is_active' => true,
        ]);

        // 5. Test Categories
        $catHem = TestCategory::create([
            'name' => 'Hematology & Clinical Pathology',
            'code' => 'HEM',
            'slug' => 'hematology',
            'description' => 'Automated cell counting, coagulation, blood morphology and blood grouping.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $catBio = TestCategory::create([
            'name' => 'Biochemistry & Clinical Chemistry',
            'code' => 'BIO',
            'slug' => 'biochemistry',
            'description' => 'Renal, hepatic, lipid and metabolic profile analyzers.',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $catEndo = TestCategory::create([
            'name' => 'Endocrinology & Immunology',
            'code' => 'ENDO',
            'slug' => 'endocrinology',
            'description' => 'Thyroid, fertility, viral markers, and chemiluminescence assays.',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $catRad = TestCategory::create([
            'name' => 'Radiology & Medical Imaging',
            'code' => 'RAD',
            'slug' => 'radiology-imaging',
            'description' => 'Digital Radiography (X-Ray), 4D Ultrasonography and CT Scans.',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        // 6. Tests & Parameters & Reference Intervals
        // Test A: Complete Blood Count (CBC)
        $testCbc = Test::create([
            'test_category_id' => $catHem->id,
            'code' => 'CBC',
            'name' => 'Complete Blood Count (CBC) with ESR',
            'slug' => 'complete-blood-count',
            'sample_type' => 'Blood',
            'collection_tube' => 'EDTA Tube (Purple Top)',
            'specimen_instructions' => 'Fasting not required. 2-3 mL venous blood in K2-EDTA tube.',
            'price' => 35.00,
            'cost_price' => 8.50,
            'tat_hours' => 3,
            'method' => 'Fluorescence Flow Cytometry / Automated Impedance (Sysmex XN-1000)',
            'report_type' => 'numeric_tabular',
            'clinical_significance' => 'Evaluates cellular components of peripheral blood for anemia, infection, leukemia and platelet disorders.',
            'is_active' => true,
        ]);

        $p_hgb = TestParameter::create(['test_id' => $testCbc->id, 'name' => 'Hemoglobin', 'code' => 'HGB', 'unit' => 'g/dL', 'sort_order' => 1]);
        $p_wbc = TestParameter::create(['test_id' => $testCbc->id, 'name' => 'Total WBC Count', 'code' => 'WBC', 'unit' => '10^3/uL', 'sort_order' => 2]);
        $p_plt = TestParameter::create(['test_id' => $testCbc->id, 'name' => 'Platelet Count', 'code' => 'PLT', 'unit' => '10^3/uL', 'sort_order' => 3]);
        $p_rbc = TestParameter::create(['test_id' => $testCbc->id, 'name' => 'Total RBC Count', 'code' => 'RBC', 'unit' => '10^6/uL', 'sort_order' => 4]);
        $p_mcv = TestParameter::create(['test_id' => $testCbc->id, 'name' => 'MCV', 'code' => 'MCV', 'unit' => 'fL', 'sort_order' => 5]);
        $p_esr = TestParameter::create(['test_id' => $testCbc->id, 'name' => 'ESR (Westergren 1st hr)', 'code' => 'ESR', 'unit' => 'mm in 1st hr', 'sort_order' => 6]);

        TestReferenceRange::create(['test_parameter_id' => $p_hgb->id, 'gender' => 'male', 'min_value' => 13.0, 'max_value' => 17.5, 'critical_low' => 7.0, 'critical_high' => 20.0, 'text_range' => '13.0 - 17.5 g/dL']);
        TestReferenceRange::create(['test_parameter_id' => $p_hgb->id, 'gender' => 'female', 'min_value' => 12.0, 'max_value' => 15.5, 'critical_low' => 7.0, 'critical_high' => 19.0, 'text_range' => '12.0 - 15.5 g/dL']);
        TestReferenceRange::create(['test_parameter_id' => $p_wbc->id, 'gender' => 'all', 'min_value' => 4.0, 'max_value' => 11.0, 'critical_low' => 2.0, 'critical_high' => 30.0, 'text_range' => '4.0 - 11.0 10^3/uL']);
        TestReferenceRange::create(['test_parameter_id' => $p_plt->id, 'gender' => 'all', 'min_value' => 150.0, 'max_value' => 450.0, 'critical_low' => 50.0, 'critical_high' => 1000.0, 'text_range' => '150 - 450 10^3/uL']);
        TestReferenceRange::create(['test_parameter_id' => $p_rbc->id, 'gender' => 'all', 'min_value' => 4.2, 'max_value' => 5.8, 'text_range' => '4.2 - 5.8 10^6/uL']);
        TestReferenceRange::create(['test_parameter_id' => $p_mcv->id, 'gender' => 'all', 'min_value' => 80.0, 'max_value' => 100.0, 'text_range' => '80.0 - 100.0 fL']);
        TestReferenceRange::create(['test_parameter_id' => $p_esr->id, 'gender' => 'all', 'min_value' => 0.0, 'max_value' => 15.0, 'text_range' => '0 - 15 mm']);

        // Test B: Lipid Profile
        $testLipid = Test::create([
            'test_category_id' => $catBio->id,
            'code' => 'LIPID',
            'name' => 'Fasting Lipid Profile',
            'slug' => 'lipid-profile',
            'sample_type' => 'Blood',
            'collection_tube' => 'Serum Gel Tube (Gold Top)',
            'specimen_instructions' => 'Strict 10-12 hours overnight fasting mandatory. Plain water allowed.',
            'price' => 45.00,
            'cost_price' => 10.00,
            'tat_hours' => 4,
            'method' => 'Enzymatic Colorimetric (Roche Cobas c501)',
            'report_type' => 'numeric_tabular',
            'clinical_significance' => 'Assesses cardiovascular atherogenic risk and dyslipidemia.',
            'is_active' => true,
        ]);

        $p_tchol = TestParameter::create(['test_id' => $testLipid->id, 'name' => 'Total Cholesterol', 'code' => 'TCHOL', 'unit' => 'mg/dL', 'sort_order' => 1]);
        $p_hdl = TestParameter::create(['test_id' => $testLipid->id, 'name' => 'HDL Cholesterol (Good)', 'code' => 'HDL', 'unit' => 'mg/dL', 'sort_order' => 2]);
        $p_ldl = TestParameter::create(['test_id' => $testLipid->id, 'name' => 'LDL Cholesterol (Bad)', 'code' => 'LDL', 'unit' => 'mg/dL', 'sort_order' => 3]);
        $p_trig = TestParameter::create(['test_id' => $testLipid->id, 'name' => 'Triglycerides', 'code' => 'TG', 'unit' => 'mg/dL', 'sort_order' => 4]);

        TestReferenceRange::create(['test_parameter_id' => $p_tchol->id, 'gender' => 'all', 'min_value' => 125.0, 'max_value' => 200.0, 'critical_high' => 300.0, 'text_range' => '< 200 mg/dL (Desirable)']);
        TestReferenceRange::create(['test_parameter_id' => $p_hdl->id, 'gender' => 'male', 'min_value' => 40.0, 'max_value' => 60.0, 'text_range' => '> 40 mg/dL']);
        TestReferenceRange::create(['test_parameter_id' => $p_ldl->id, 'gender' => 'all', 'min_value' => 0.0, 'max_value' => 100.0, 'critical_high' => 190.0, 'text_range' => '< 100 mg/dL (Optimal)']);
        TestReferenceRange::create(['test_parameter_id' => $p_trig->id, 'gender' => 'all', 'min_value' => 50.0, 'max_value' => 150.0, 'critical_high' => 500.0, 'text_range' => '< 150 mg/dL (Normal)']);

        // Test C: Liver Function Test (LFT)
        $testLft = Test::create([
            'test_category_id' => $catBio->id,
            'code' => 'LFT',
            'name' => 'Liver Function Panel (LFT)',
            'slug' => 'liver-function-test',
            'sample_type' => 'Blood',
            'collection_tube' => 'Serum Gel Tube (Gold Top)',
            'specimen_instructions' => 'Fasting preferred. Avoid alcohol 24 hours prior.',
            'price' => 40.00,
            'cost_price' => 9.00,
            'tat_hours' => 4,
            'method' => 'Spectrophotometry / Photometric UV',
            'report_type' => 'numeric_tabular',
            'is_active' => true,
        ]);

        $p_tbil = TestParameter::create(['test_id' => $testLft->id, 'name' => 'Total Bilirubin', 'code' => 'TBIL', 'unit' => 'mg/dL', 'sort_order' => 1]);
        $p_sgpt = TestParameter::create(['test_id' => $testLft->id, 'name' => 'SGPT / ALT', 'code' => 'ALT', 'unit' => 'U/L', 'sort_order' => 2]);
        $p_sgot = TestParameter::create(['test_id' => $testLft->id, 'name' => 'SGOT / AST', 'code' => 'AST', 'unit' => 'U/L', 'sort_order' => 3]);
        $p_alp = TestParameter::create(['test_id' => $testLft->id, 'name' => 'Alkaline Phosphatase (ALP)', 'code' => 'ALP', 'unit' => 'U/L', 'sort_order' => 4]);

        TestReferenceRange::create(['test_parameter_id' => $p_tbil->id, 'gender' => 'all', 'min_value' => 0.2, 'max_value' => 1.2, 'critical_high' => 12.0, 'text_range' => '0.2 - 1.2 mg/dL']);
        TestReferenceRange::create(['test_parameter_id' => $p_sgpt->id, 'gender' => 'all', 'min_value' => 5.0, 'max_value' => 45.0, 'critical_high' => 500.0, 'text_range' => 'Up to 45 U/L']);
        TestReferenceRange::create(['test_parameter_id' => $p_sgot->id, 'gender' => 'all', 'min_value' => 5.0, 'max_value' => 40.0, 'critical_high' => 500.0, 'text_range' => 'Up to 40 U/L']);
        TestReferenceRange::create(['test_parameter_id' => $p_alp->id, 'gender' => 'all', 'min_value' => 44.0, 'max_value' => 147.0, 'text_range' => '44 - 147 U/L']);

        // Test D: Kidney Function (Serum Creatinine & Urea)
        $testKft = Test::create([
            'test_category_id' => $catBio->id,
            'code' => 'KFT',
            'name' => 'Kidney Function Test (Serum Creatinine & Urea)',
            'slug' => 'kidney-function-test',
            'sample_type' => 'Blood',
            'collection_tube' => 'Serum Gel Tube (Gold Top)',
            'price' => 30.00,
            'cost_price' => 6.00,
            'tat_hours' => 3,
            'method' => 'Jaffe Modified Kinetic / Enzymatic',
            'report_type' => 'numeric_tabular',
            'is_active' => true,
        ]);

        $p_creat = TestParameter::create(['test_id' => $testKft->id, 'name' => 'Serum Creatinine', 'code' => 'CREAT', 'unit' => 'mg/dL', 'sort_order' => 1]);
        $p_urea = TestParameter::create(['test_id' => $testKft->id, 'name' => 'Blood Urea', 'code' => 'UREA', 'unit' => 'mg/dL', 'sort_order' => 2]);

        TestReferenceRange::create(['test_parameter_id' => $p_creat->id, 'gender' => 'male', 'min_value' => 0.7, 'max_value' => 1.3, 'critical_high' => 6.0, 'text_range' => '0.7 - 1.3 mg/dL']);
        TestReferenceRange::create(['test_parameter_id' => $p_creat->id, 'gender' => 'female', 'min_value' => 0.6, 'max_value' => 1.1, 'critical_high' => 5.0, 'text_range' => '0.6 - 1.1 mg/dL']);
        TestReferenceRange::create(['test_parameter_id' => $p_urea->id, 'gender' => 'all', 'min_value' => 15.0, 'max_value' => 45.0, 'critical_high' => 100.0, 'text_range' => '15 - 45 mg/dL']);

        // Test E: Thyroid Stimulating Hormone (TSH)
        $testTsh = Test::create([
            'test_category_id' => $catEndo->id,
            'code' => 'TSH',
            'name' => 'Thyroid Stimulating Hormone (TSH, Ultrasensitive)',
            'slug' => 'tsh-thyroid-test',
            'sample_type' => 'Blood',
            'collection_tube' => 'Serum Gel Tube',
            'price' => 25.00,
            'cost_price' => 5.00,
            'tat_hours' => 4,
            'method' => 'Chemiluminescent Microparticle Immunoassay (CMIA)',
            'report_type' => 'numeric_tabular',
            'is_active' => true,
        ]);

        $p_tsh = TestParameter::create(['test_id' => $testTsh->id, 'name' => 'TSH (Ultrasensitive)', 'code' => 'TSH', 'unit' => 'uIU/mL', 'sort_order' => 1]);
        TestReferenceRange::create(['test_parameter_id' => $p_tsh->id, 'gender' => 'all', 'min_value' => 0.35, 'max_value' => 4.94, 'critical_low' => 0.05, 'critical_high' => 20.0, 'text_range' => '0.35 - 4.94 uIU/mL']);

        // Test F: Digital Chest X-Ray (P/A View)
        $testCxr = Test::create([
            'test_category_id' => $catRad->id,
            'code' => 'CXR',
            'name' => 'Digital Chest X-Ray (P/A View)',
            'slug' => 'digital-chest-x-ray',
            'sample_type' => 'Imaging',
            'specimen_instructions' => 'Wear metallic-free clothing. Inform technician if pregnant.',
            'price' => 30.00,
            'cost_price' => 4.00,
            'tat_hours' => 2,
            'method' => 'High-Frequency Digital Radiography (DR)',
            'report_type' => 'imaging_findings',
            'is_active' => true,
        ]);

        // 7. Health Packages
        $pkgExec = TestPackage::create([
            'name' => 'Executive Comprehensive Health Checkup',
            'code' => 'PKG-EXEC-01',
            'slug' => 'executive-health-checkup',
            'description' => 'A complete vital check encompassing Complete Blood Count, Liver Panel, Kidney Markers, and Fasting Lipid Profile.',
            'price' => 115.00,
            'discount_percentage' => 25.00,
            'is_popular' => true,
            'is_active' => true,
        ]);
        $pkgExec->tests()->attach([$testCbc->id, $testLipid->id, $testLft->id, $testKft->id]);

        $pkgCardio = TestPackage::create([
            'name' => 'Cardiac & Lipid Screening Package',
            'code' => 'PKG-CARD-02',
            'slug' => 'cardiac-lipid-package',
            'description' => 'Targeted evaluation for cardiovascular risk, lipid fractions, CBC and renal filtration.',
            'price' => 85.00,
            'discount_percentage' => 20.00,
            'is_popular' => true,
            'is_active' => true,
        ]);
        $pkgCardio->tests()->attach([$testLipid->id, $testCbc->id, $testKft->id]);

        $pkgDiab = TestPackage::create([
            'name' => 'Diabetic Metabolic Wellness Profile',
            'code' => 'PKG-DIAB-03',
            'slug' => 'diabetic-profile',
            'description' => 'Key indicators for diabetic patients including renal safety, liver enzymes, and lipid balance.',
            'price' => 75.00,
            'discount_percentage' => 15.00,
            'is_popular' => true,
            'is_active' => true,
        ]);
        $pkgDiab->tests()->attach([$testCbc->id, $testKft->id, $testLipid->id]);

        // 8. Reagents Inventory
        $reag1 = Reagent::create([
            'branch_id' => $centralBranch->id,
            'name' => 'Sysmex Cellpack DCL Diluent',
            'code' => 'REAG-DCL-001',
            'brand' => 'Sysmex Corporation',
            'lot_number' => 'LOT-2026-XN88',
            'unit' => 'Liters',
            'stock_quantity' => 150.00,
            'min_alert_threshold' => 30.00,
            'unit_cost' => 12.50,
            'expiry_date' => now()->addMonths(14),
            'storage_conditions' => '15°C - 30°C. Protect from direct sunlight.',
        ]);

        $reag2 = Reagent::create([
            'branch_id' => $centralBranch->id,
            'name' => 'Roche Cholesterol Gen.2 Enzymatic Kit',
            'code' => 'REAG-CHOL-002',
            'brand' => 'Roche Diagnostics',
            'lot_number' => 'LOT-2026-RC42',
            'unit' => 'Tests',
            'stock_quantity' => 450.00,
            'min_alert_threshold' => 100.00,
            'unit_cost' => 0.45,
            'expiry_date' => now()->addMonths(9),
            'storage_conditions' => '2°C - 8°C (Refrigerated)',
        ]);

        $reag3 = Reagent::create([
            'branch_id' => $centralBranch->id,
            'name' => 'Serum Creatinine Jaffe Reagent Kit',
            'code' => 'REAG-CREAT-003',
            'brand' => 'BioSystems S.A.',
            'lot_number' => 'LOT-2026-BS19',
            'unit' => 'Tests',
            'stock_quantity' => 18.00, // Trigger low stock warning!
            'min_alert_threshold' => 50.00,
            'unit_cost' => 0.35,
            'expiry_date' => now()->addMonths(5),
            'storage_conditions' => '2°C - 8°C',
        ]);

        // 9. Patients & Families
        $pat1 = Patient::create([
            'branch_id' => $centralBranch->id,
            'mrn' => 'MRN-2026-00001',
            'slug' => 'tanvir-hossain-7a4b',
            'name' => 'Tanvir Hossain',
            'phone' => '+880 1712-345678',
            'email' => 'tanvir.patient@test.com',
            'gender' => 'male',
            'date_of_birth' => '1985-04-12',
            'age_years' => 41,
            'blood_group' => 'B+',
            'address' => 'House 14, Road 5, Dhanmondi, Dhaka',
            'national_id' => '19852691234567890',
            'emergency_contact_name' => 'Rehana Begum (Wife)',
            'emergency_contact_phone' => '+880 1712-999888',
            'allergies' => 'Penicillin allergy noted in 2018.',
            'medical_history' => 'Hypertension managed on Amlodipine 5mg. Mild dyslipidemia.',
            'portal_pin' => '1234',
        ]);

        $pat2 = Patient::create([
            'branch_id' => $centralBranch->id,
            'mrn' => 'MRN-2026-00002',
            'slug' => 'rehana-begum-9c2d',
            'name' => 'Rehana Begum',
            'phone' => '+880 1712-999888',
            'gender' => 'female',
            'date_of_birth' => '1989-09-20',
            'age_years' => 37,
            'blood_group' => 'O+',
            'address' => 'House 14, Road 5, Dhanmondi, Dhaka',
            'portal_pin' => '5678',
        ]);

        PatientFamily::create([
            'patient_id' => $pat1->id,
            'relative_patient_id' => $pat2->id,
            'relationship' => 'Spouse',
            'can_view_reports' => true,
        ]);

        $pat3 = Patient::create([
            'branch_id' => $downtownBranch->id,
            'mrn' => 'MRN-2026-00003',
            'slug' => 'kamal-uddin-ahmed-3f1a',
            'name' => 'Kamal Uddin Ahmed',
            'phone' => '+880 1819-456789',
            'gender' => 'male',
            'date_of_birth' => '1968-11-03',
            'age_years' => 58,
            'blood_group' => 'A+',
            'address' => '45 Elephant Road, Dhaka',
            'medical_history' => 'Type 2 Diabetes Mellitus (10 years), Chronic Kidney Disease Stage 2.',
            'portal_pin' => '4321',
        ]);

        // 10. Orders, Samples, and Completed Reports with Real Clinical Data & AI Summaries
        // Order 1: Tanvir Hossain (CBC & Lipid Profile)
        $ord1 = Order::create([
            'order_number' => 'ORD-2026-00001',
            'patient_id' => $pat1->id,
            'branch_id' => $centralBranch->id,
            'doctor_id' => $doc1->id,
            'created_by' => $receptionist->id,
            'order_date' => now()->subHours(5),
            'priority' => 'normal',
            'subtotal' => 80.00,
            'discount_amount' => 10.00,
            'total_amount' => 70.00,
            'paid_amount' => 70.00,
            'payment_status' => 'paid',
            'status' => 'completed',
        ]);

        $item1_cbc = OrderItem::create([
            'order_id' => $ord1->id,
            'test_id' => $testCbc->id,
            'price' => 35.00,
            'discount' => 5.00,
            'final_price' => 30.00,
            'status' => 'completed',
        ]);

        $smp1 = Sample::create([
            'barcode' => 'SMP-2026-884210',
            'order_item_id' => $item1_cbc->id,
            'patient_id' => $pat1->id,
            'branch_id' => $centralBranch->id,
            'sample_type' => 'Blood',
            'collection_tube' => 'EDTA Tube',
            'status' => Sample::STATUS_VERIFIED,
            'collected_at' => now()->subHours(5),
            'collected_by' => $technologist->id,
            'received_at' => now()->subHours(4),
            'received_by' => $technologist->id,
            'verified_at' => now()->subHours(1),
            'verified_by' => $pathologist->id,
        ]);

        SampleCustodyLog::create([
            'sample_id' => $smp1->id,
            'user_id' => $technologist->id,
            'action' => 'Phlebotomy blood draw completed and barcode scanned.',
            'from_location' => 'Collection Booth 2',
            'to_location' => 'Central Reception',
            'logged_at' => now()->subHours(5),
        ]);

        SampleCustodyLog::create([
            'sample_id' => $smp1->id,
            'user_id' => $technologist->id,
            'action' => 'Sample loaded onto Sysmex XN-1000 automated analyzer.',
            'from_location' => 'Central Reception',
            'to_location' => 'Hematology Analyzer #1',
            'logged_at' => now()->subHours(3),
        ]);

        $rep1 = Report::create([
            'report_number' => 'RPT-2026-00001',
            'slug' => 'rep-tanvir-cbc-992a',
            'order_id' => $ord1->id,
            'order_item_id' => $item1_cbc->id,
            'patient_id' => $pat1->id,
            'branch_id' => $centralBranch->id,
            'pathologist_id' => $pathologist->id,
            'technician_id' => $technologist->id,
            'status' => 'published',
            'clinical_impression' => 'Mild microcytic hypochromic picture. Hemoglobin is slightly below adult male reference interval. Suggest serum ferritin correlation.',
            'ai_summary' => "Hello Tanvir, your Complete Blood Count has been analyzed by our automated laboratory systems.\n\n### Overall Summary\nYour overall blood cells and platelets are healthy, but your Hemoglobin level is slightly low at 11.8 g/dL (normal male range is 13.0 - 17.5 g/dL). This suggests mild anemia, which may cause occasional fatigue or weakness.\n\n### Key Highlights\n- Platelet count and White Blood Cells (WBC) are completely normal, showing healthy clotting and immune system response.\n- Red Blood Cell size (MCV) is slightly on the lower side.\n\n### Recommended Next Steps\n- Incorporate iron-rich foods like leafy greens, lean meats, and lentils into your meals.\n- Share this report with Dr. A. K. Azad during your next consultation to see if an iron supplement or dietary change is recommended.\n\n> Notice: This AI summary is for informational guidance only. Please consult your physician for formal clinical advice.",
            'ai_critical_flags' => [
                [
                    'parameter' => 'Hemoglobin',
                    'value' => 11.8,
                    'unit' => 'g/dL',
                    'flag' => 'low',
                    'reference' => '13.0 - 17.5 g/dL',
                    'severity' => 'WARNING',
                    'clinical_note' => 'Possible microcytic/normocytic anemia; correlate with serum ferritin & RBC indices.'
                ]
            ],
            'ai_doctor_notes' => "### Clinical Decision Support — Diagnostic Correlations\n- Patient: Tanvir Hossain (41 Y, Male)\n- Deviations: Hemoglobin 11.8 g/dL (low), MCV 78 fL (mild microcytosis).\n- Differential Considerations: Nutritional iron deficiency vs early anemia of chronic disease. Rule out occult GI blood loss if persistent.\n- Suggested Follow-up: Serum Ferritin, TIBC, and Stool for Occult Blood if indicated.",
            'verification_hash' => hash('sha256', 'RPT-2026-00001-TANVIR-CBC-AUTH-TOKEN-2026'),
            'qr_code_url' => url('/verify-report/' . hash('sha256', 'RPT-2026-00001-TANVIR-CBC-AUTH-TOKEN-2026')),
            'verified_at' => now()->subHours(1),
            'published_at' => now()->subHours(1),
        ]);

        ReportValue::create(['report_id' => $rep1->id, 'test_parameter_id' => $p_hgb->id, 'value_numeric' => 11.8, 'flag' => 'low', 'reference_range_snapshot' => '13.0 - 17.5 g/dL', 'unit' => 'g/dL']);
        ReportValue::create(['report_id' => $rep1->id, 'test_parameter_id' => $p_wbc->id, 'value_numeric' => 6.8, 'flag' => 'normal', 'reference_range_snapshot' => '4.0 - 11.0 10^3/uL', 'unit' => '10^3/uL']);
        ReportValue::create(['report_id' => $rep1->id, 'test_parameter_id' => $p_plt->id, 'value_numeric' => 245.0, 'flag' => 'normal', 'reference_range_snapshot' => '150 - 450 10^3/uL', 'unit' => '10^3/uL']);
        ReportValue::create(['report_id' => $rep1->id, 'test_parameter_id' => $p_rbc->id, 'value_numeric' => 4.6, 'flag' => 'normal', 'reference_range_snapshot' => '4.2 - 5.8 10^6/uL', 'unit' => '10^6/uL']);
        ReportValue::create(['report_id' => $rep1->id, 'test_parameter_id' => $p_mcv->id, 'value_numeric' => 78.0, 'flag' => 'low', 'reference_range_snapshot' => '80.0 - 100.0 fL', 'unit' => 'fL']);
        ReportValue::create(['report_id' => $rep1->id, 'test_parameter_id' => $p_esr->id, 'value_numeric' => 12.0, 'flag' => 'normal', 'reference_range_snapshot' => '0 - 15 mm', 'unit' => 'mm in 1st hr']);

        // Invoice for Order 1
        $inv1 = Invoice::create([
            'invoice_number' => 'INV-2026-00001',
            'order_id' => $ord1->id,
            'patient_id' => $pat1->id,
            'branch_id' => $centralBranch->id,
            'cashier_id' => $receptionist->id,
            'subtotal' => 80.00,
            'discount_amount' => 10.00,
            'tax_amount' => 0.00,
            'net_total' => 70.00,
            'paid_amount' => 70.00,
            'balance_due' => 0.00,
            'payment_status' => 'paid',
            'payment_method' => 'card',
            'transaction_reference' => 'POS-VISA-78921',
            'is_reconciled' => true,
            'reconciled_at' => now()->subHours(1),
        ]);

        // Referral Commission for Doc 1
        $doc1->commissions()->create([
            'order_id' => $ord1->id,
            'invoice_id' => $inv1->id,
            'order_total' => 70.00,
            'commission_rate' => 15.00,
            'commission_amount' => 10.50,
            'status' => 'accrued',
        ]);

        // Appointments & Tokens for Queue
        Appointment::create([
            'appointment_number' => 'APT-2026-00001',
            'token_number' => 'A-012',
            'patient_id' => $pat1->id,
            'branch_id' => $centralBranch->id,
            'test_id' => $testCbc->id,
            'appointment_date' => today(),
            'time_slot' => '09:00 - 10:00',
            'queue_status' => 'completed',
            'is_walk_in' => false,
            'completed_at' => now()->subHours(2),
        ]);

        Appointment::create([
            'appointment_number' => 'APT-2026-00002',
            'token_number' => 'A-014',
            'patient_id' => $pat3->id,
            'branch_id' => $centralBranch->id,
            'test_package_id' => $pkgDiab->id,
            'appointment_date' => today(),
            'time_slot' => '10:00 - 11:00',
            'queue_status' => 'in_service',
            'is_walk_in' => false,
            'symptoms_chief_complaint' => 'Polyuria, fatigue, diabetic monitoring',
            'checked_in_at' => now()->subMinutes(20),
            'called_at' => now()->subMinutes(10),
        ]);

        Appointment::create([
            'appointment_number' => 'APT-2026-00003',
            'token_number' => 'W-005',
            'patient_id' => $pat2->id,
            'branch_id' => $centralBranch->id,
            'test_id' => $testTsh->id,
            'appointment_date' => today(),
            'time_slot' => '10:00 - 11:00',
            'queue_status' => 'called',
            'is_walk_in' => true,
            'checked_in_at' => now()->subMinutes(15),
            'called_at' => now()->subMinutes(2),
        ]);

        // AI Log entries for Studio
        AiLog::create([
            'branch_id' => $centralBranch->id,
            'user_id' => $pathologist->id,
            'report_id' => $rep1->id,
            'feature' => 'report_summarizer',
            'provider' => 'gemini',
            'model' => 'gemini-1.5-flash',
            'input_tokens' => 380,
            'output_tokens' => 245,
            'estimated_cost_usd' => 0.000204,
            'latency_ms' => 340,
            'status' => 'success',
        ]);

        AiLog::create([
            'branch_id' => $centralBranch->id,
            'user_id' => $pathologist->id,
            'report_id' => $rep1->id,
            'feature' => 'critical_flagging',
            'provider' => 'gemini',
            'model' => 'gemini-1.5-flash',
            'input_tokens' => 210,
            'output_tokens' => 85,
            'estimated_cost_usd' => 0.000082,
            'latency_ms' => 195,
            'status' => 'success',
        ]);
    }
}
