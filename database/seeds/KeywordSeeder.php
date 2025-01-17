<?php

use Illuminate\Database\Seeder;
use App\Models\Keyword;
use App\Models\User;
use App\Models\State;
use App\Models\Cities;

class KeywordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('keywords')->delete();
        $mainSuperUserId = User::where([
            'email' => 'fulladmin@nurseify.io'
        ])->get()->first()->id;

        $this->insertKeywords($mainSuperUserId);
    }

    private function insertKeywords($mainSuperUserId)
    {
        $keywords = $this->keywordData();
        foreach ($keywords as $key => $value) {
            foreach($value as $item){
                if (!Keyword::where(['filter' => $key, 'title' => $item])->exists()) {
                    factory(Keyword::class)->create([
                        'created_by' => $mainSuperUserId,
                        'filter' => $key,
                        'title' => $item,
                    ]);
                }
            }
        }
    }

    private function keywordData()
    {
        return [
            'AssignmentDuration' => [
                '1 shift/wk',
                '1 shift/2wks',
                '3 shifts/13wks',
                'no shift cancelations',
                'no preference',
            ],
            'FacilityType' => [
                'Acute Care Hospital',
                'Behavioral Health Hospital',
                'Ambulatory Care Facility (includes surgery centers, free-standing emergency rooms, and clinics)',
                'Assisted Living',
                'Skilled Nursing',
                'Other'
            ],
            'LeadershipRoles' => [
                'Charge Nurse or Clinical Nurse Coordinator',
                'Supervisor',
                'Manager',
                'Director',
                'Assistant Chief Nursing Officer (ACNO)',
                'Chief Nursing Officer (CNO)'
            ],
            'NursingDegree' => [
                'Associate\'s or ADN',
                'Bachelor\'s or BSN',
                'Master\'s or MSN',
                'Doctorate\'s or DNP'
            ],
            'Speciality' => [
                'Acute Care',
                'Addiction Medicine',
                'Administrative',
                'Admission',
                'Adult Medicine',
                'Ambulatory Care',
                'American Sign Language Teacher',
                'Anesthesia',
                'Anesthesia Technician',
                'Apheresis',
                'Assistant Director of Nursing',
                'Assistant Manager',
                'Audiologist',
                'Behavioral Health',
                'Bereavement Specialist',
                'Biomed Technician',
                'Board Certified Behavioral Analyst (BCBA)',
                'Bone Marrow Transplant',
                'Burn ICU',
                'Cardiac Cath Lab',
                'Cardiac Progressive Care Unit',
                'Cardiac Stepdown',
                'Cardiology',
                'Cardiovascular Technologist',
                'Cardiovascular/Cardiothoracic Surgery',
                'Case Management',
                'Case Management Acute Care',
                'Case Management Home Health',
                'Case Management Insurance',
                'Cath Lab Technologist',
                'CCU - Coronary Care',
                'Centralized Cardiac Monitoring Technician',
                'Centralized Sterile Technician',
                'Certified Occupational Therapist Assistant',
                'Certified Ophthalmic Medical Technologist',
                'Certified Surgical Technologist',
                'Clerical',
                'Clinic',
                'Clinical',
                'Clinical Document Improvement Specialist',
                'Clinical Fellowship Year',
                'Clinical Lab Assistant',
                'Clinical Lab Scientist (CLS)',
                'Clinical Liaison',
                'Clinical Nurse Coordinator',
                'Clinical Nurse Specialist',
                'Clinical Support Specialist',
                'Clinical Trial',
                'Correctional',
                'Critical Care/Intensive Care',
                'CT Technologist',
                'CTICU - Cardiothoracic Intensive Care',
                'CVICU',
                'CVOR',
                'CVOR Technologist',
                'CVPICU',
                'Cytogenetics Technologist',
                'Cytotechnologist',
                'Dermatology',
                'Dialysis',
                'Dialysis Technician',
                'Dietary Aide',
                'Director of Nursing',
                'Dosimetrist',
                'DOU - Direct Observation Unit',
                'Echo Technician',
                'Echo Technologist',
                'Echo-Vascular Technician',
                'ECMO',
                'ED - Emergency Department',
                'Educator',
                'EEG Technician',
                'EEG Technologist',
                'EKG Technician',
                'Electrophysiology Lab',
                'Electrophysiology Technician',
                'Emergency Medicine',
                'EMT / ER Technician',
                'Endocrinology',
                'Endoscopy',
                'Endoscopy Technician',
                'Faculty',
                'Family Practice',
                'Field Supervisor',
                'First Assist',
                'First Assist Technician',
                'Flight Nurse or Critical Care Flight Nurse',
                'Float',
                'Gastroenterology',
                'General Surgery',
                'Geriatrics',
                'Gerontology',
                'GI Lab',
                'Health Services Administrator',
                'Hematology & Oncology',
                'Histology Technician',
                'Home Health',
                'Hospice',
                'Hospitalist',
                'House Supervisor',
                'ICU - Intensive Care Unit',
                'Infection Control',
                'Infusion',
                'Integrative Health',
                'Intermediate Care',
                'Internal Medicine',
                'Interventional Radiology',
                'Interventional Radiology Technologist',
                'Labor and Delivery',
                'Lactation RN',
                'Licensed Clinical Social Worker',
                'Licensed Independent Clinical Social Worker',
                'Licensed Marriage and Family Therapist',
                'Licensed Professional Clinical Counselor',
                'Long Term Acute Care',
                'Long Term Care',
                'Mammography Technician',
                'Manager',
                'Master of Social Work',
                'Maternal - Newborn',
                'Med Surg',
                'Med Surg / Telemetry',
                'Medical Lab Scientist',
                'Medical Lab Technician',
                'Medical Technologist',
                'Medical Unit',
                'Mental Health',
                'MICU - Medical Intensive Care Unit',
                'Midwife',
                'Mobile Testing',
                'Monitor Technician',
                'MRI Technologist',
                'Multi-modality Technologist',
                'Neonatology',
                'Nephrology',
                'Neuro ICU',
                'Neuro PCU',
                'Neurology',
                'Neurosurgery',
                'New Graduate',
                'NICU - Neonatal Intensive Care',
                'Nuclear Medicine Technologist',
                'Nurse Extern',
                'Nurse Navigator',
                'Nurse Resident',
                'Nursing Home',
                'OB Technician',
                'Obstetrics / Gynecology',
                'Occupational Health',
                'Occupational Medicine',
                'Occupational Therapist',
                'Occupational Therapist Assistant',
                'Ophthalmic Assistant',
                'Ophthalmic Technician',
                'Ophthalmology',
                'OR - Operating Room',
                'OR Circulate',
                'OR Scrub RN',
                'Ortho Trauma',
                'Orthopedic Neurology',
                'Orthopedic Surgery',
                'Orthopedics',
                'Otolaryngology',
                'PACU - Post Anesthetic Care',
                'Pain Management',
                'Palliative',
                'Palliative Care',
                'Paramedic',
                'Pathologists\' Assistant',
                'Pathology Assistant',
                'Patient Care Tech',
                'PCU - Progressive Care Unit',
                'Pediatric Cardiac Cath Lab',
                'Pediatric CVOR',
                'Pediatric Hematology / Oncology',
                'Pediatric Hospitalist',
                'Pediatric MRI Technologist',
                'Pediatric PCU - Progressive Care Unit',
                'Pediatric Radiology Technologist',
                'Pediatrics',
                'Pediatrics CVICU',
                'Pediatrics ER - Emergency Room',
                'Pediatrics OR - Operating Room',
                'Pediatrics PACU - Post Anesthetic Care',
                'Perfusionist',
                'Perioperative',
                'Pharmacist',
                'Pharmacy Technician',
                'Phlebotomist',
                'Physical Therapist',
                'Physical Therapy Assistant',
                'Physicist',
                'PICU - Pediatric Intensive Care',
                'PMR Physical Medicine & Rehab',
                'Polysomnographer',
                'Post Partum',
                'Preoperative',
                'Private Duty',
                'Psychiatric',
                'Psychiatry',
                'Psychologist',
                'Public Health',
                'Pulmonology',
                'Radiation Therapist',
                'Radiology',
                'Radiology Technician',
                'Radiology Technologist',
                'RDCD Dietician',
                'Recreation/Recreational Therapist',
                'Recruiter',
                'Registered Respiratory Therapist',
                'Rehab Technician',
                'Rehabilitation',
                'Rehabilitation Therapist',
                'Research / Clinical Research',
                'Respiratory Care Practitioner',
                'Respiratory Technician',
                'Respiratory Therapist',
                'Risk Manager',
                'School Nurse',
                'School Psychologist (Certified)',
                'SICU - Surgical Intensive Care',
                'Skilled Nursing Facility',
                'Social Worker',
                'Sonography Technician',
                'Special Education Teacher',
                'Special Procedure Technician',
                'Special Procedures',
                'Special Procedures Technologist',
                'Speech Language Pathologist',
                'Speech Language Pathologist Assistant (SLPA)',
                'Stepdown',
                'Sterile Processing Technician',
                'Stroke',
                'Surgical First Assistant',
                'Surgical ICU Stepdown',
                'Surgical Oncology',
                'Surgical Technician',
                'Surgical Technologist',
                'System Float',
                'Teacher of the Deaf and Hard of Hearing',
                'Telehealth',
                'Telemetry',
                'Telemetry PCU',
                'Telemetry Technician',
                'Transplant',
                'Transport',
                'Transporter Multimodality Technician',
                'Trauma ICU',
                'Trauma Program Manager',
                'Ultrasound Technologist',
                'Urgent Care',
                'Urology',
                'Utilization Review',
                'Vaccination',
                'Vascular Access',
                'Vascular Interventional Technician',
                'Vascular Technologist',
                'Vision Assistant',
                'Visual Impairments Teacher',
                'Women\'s Services',
                'Wound Care',
                'X-Ray Technician',
                'In-Patient',
                'Outpatient Surgery',
                'Crisis Stabilization Unit',
            ],
            'GeographicPreference' => [
                'Local Traveler (within 25 miles)',
                'Regional Traveler (within 100 miles)',
                'National Traveler (anywhere in the US)',
                'International Traveler (anywhere in North America or Europe)'
            ],
            'Certification' => [
                'BLS',
                'ACLS',
                'PALS',
                'NRP',
                'NIHSS',
                'TNCC',
                'AWHONN',
                'STABLE',
                'LA Fire Card',
                'CMA',
                'CNA',
                'ARDMS',
                'CPI',
                'NBRC',
                'RCIS',
                'Management of Assaultive Behavior',
                'IV Therapy',
                'Chemotherapy',
                'R.R.A',
                'R.T',
                'R.T.(MR)(ARRT)',
                'R.T.(N)(ARRT)',
                'R.T.(R)(ARRT)',
                'R.T.(R)(CT)(ARRT)',
                'R.T.(R)(CT)(MR)(ARRT)',
                'R.T.(R)(M)(ARRT)',
                'R.T.(R)(M)(CT)(ARRT)',
                'R.T.(R)(MR)(ARRT)',
                'R.T.(R)(N)(ARRT)',
                'R.T.(R)(T)(ARRT)',
                'R.T.(S)(ARRT)',
                'R.T.(T)(ARRT)',
                'R.T.(VS)(ARRT)',
                'R.T.(R)(BD)(ARRT)',
                'R.T.(R)(CI)(ARRT)',
                'R.T.(CT)(ARRT)',
                'R.T.(R)(CV)(ARRT)',
                'R.T.(R)(M)(BS)(ARRT)',
                'R.T.(R)(M)(QM)(ARRT)',
                'R.T.(R)(VI)(ARRT)',
                'R.T.(R)(N)(CT)(ARRT)',
                'R.T.(R)(T)(CT)(ARRT)'
            ],
            'EHRSoftwares' => [
                'Allscripts',
                'AmkaiSolutions',
                'Amrita Medical Solutions',
                'Angel Systems',
                'Askesis Development Group',
                'athenahealth',
                'Cantata Health',
                'Cerner Corporation',
                'CGI',
                'CoCentrix',
                'Credible',
                'DSS Inc.',
                'eClinicalWorks',
                'empowersystems',
                'Epic Systems',
                'Evident (CPSI)',
                'FEI Systems',
                'GE Healthcare',
                'Harris Healthcare',
                'Health Care Software (HCS)',
                'Healthland (CPSI)',
                'ICANotes',
                'Indian Health Services',
                'Infomedika',
                'InterSystems',
                'Marshfield Clinic',
                'MedConnect',
                'MedEZ',
                'MEDHOST',
                'Medicat',
                'MEDITECH',
                'Medsphere Systems Corporation',
                'Meta Healthcare IT Solutions',
                'MindLinc',
                'Morris Systems',
                'Netsmart Technologies',
                'NTT Data',
                'Optimus EMR',
                'Point Click Care',
                'Prognosis Innovation Healthcare',
                'PsyTech Solutions',
                'Qualifacts Systems',
                'Remarkable Health',
                'SigmaCare',
                'Sigmund Software',
                'Source Medical Solutions',
                'Technomad',
                'Tenzing Medical',
                'Uniform Data System for Medical Rehabilitation',
                'VeraSuite',
                'WorldVistA'
            ],
            'EHRProficiencyExp' => [
                'No experience',
                'Beginner (<1-year experience)',
                'Basic (1-2 year\'s experience)',
                'Proficient (~2-4 year\'s experience)',
                'Advanced (~5+ year\'s experience)'
            ],
            'Shift' => [
                '4-hour',
                '6-hour',
                '8-hour',
                '10-hour',
                '12-hour'
            ],
            'PreferredShift' => [
                'Nights & Days',
                'Days',
                'Nights',
                'Nights or Days',
            ],
            'DaisyCategory' => [
                'Extraordinary Nurse',
                'Team Award',
                'Nurse Leader',
                'Extraordinary Nursing Student',
                'Extraordinary Nursing Faculty',
                'Lifetime Achievement Award'
            ],
            'FacilityName' => [
                'Andalusia Regional Hospital',
                'Baptist Medical Center East',
                'Central Alabama Veterans Health Care System West Campus',
                'Dale Medical Center',
                'East Alabama Medical Center',
                'Elmore Community Hospital',
                'Flowers Hospital',
                'Gadsden Regional Medical Center',
                'Hale County Hospital',
                'Jack Hughston Memorial Hospital',
                'Lake Martin Community Hospital',
                'Marshall Medical Center North',
                'Medical Center Barbour',
                'North Baldwin Infirmary',
                'Parkway Medical Center',
                'Russell Medical Center',
                'Taylor Hardin Secure Medical Facility',
                'University of South Alabama Medical Center',
                'Other'
            ],
            'ClinicalProfession' => [
                'RN',
                'CNA',
                'CMA',
                'Tech / Assist',
                'Therapy',
                'Physician',
                'PA',
                'CRNA',
                'NP',
                'LPN / LVN',
                'Social Work',
                'Other Clinician',
            ],
            'Non-ClinicalProfession' => [
                'Academic',
                'Accounting',
                'Clerical',
                'Engineering',
                'Executive',
                'Food Service',
                'Health Sciences',
                'Hr/Payroll',
                'Information Technology',
                'Janitorial',
                'Light Industrial',
                'Management',
                'Medical Coding',
                'Medical Filing and Records Management',
                'Medical Laboratory',
                'Mid-Revenue Cycle Solutions',
                'Security',
                'Supervisor',
                'Unit Manager',
            ],
            'Profession' => [
                'Academic',
                'Accounting',
                'Clerical',
                'Engineering',
                'Executive',
                'Food Service',
                'Health Sciences',
                'Hr/Payroll',
                'Information Technology',
                'Janitorial',
                'Light Industrial',
                'Management',
                'Medical Coding',
                'Medical Filing and Records Management',
                'Medical Laboratory',
                'Mid-Revenue Cycle Solutions',
                'Security',
                'RN',
                'CNA',
                'CMA',
                'Tech / Assist',
                'Therapy',
                'Physician',
                'PA',
                'CRNA',
                'NP',
                'LPN / LVN',
                'Social Work',
                'Other Clinician',
            ],
            'Type'=>[
                'Clinical',
                'Non-Clinical',
            ],
            'Terms'=>[
                'Contract (Travel or Local)',
                'Perm',
                'Shift',
                'Contract to Perm',
                'Contract (Travel only)',
                'Contract (Local only)',
            ],
            'EMR'=>[
                'EPIC',
                'Cerner',
                'Pyxis',
                'Athena',
                'NXStage',
                'Meditech',
                'Allscripts',
                'CPSI/Evident',
                'Paragon',
                'Point Click Care',
                'Centricity',
                'McKesson',
                'Protouch',
                'SunQuest',
                'AHT',
                'Brightree',
                'DocuTAP',
                'Homecare Homebase HCHB',
                'MedHost',
                'Nuance',
                'Phillips',
                'ReDoc',
                'RPMS',
                'TruChart'
            ],
            'ClinicalSetting' => [
                'Corrections',
                'School',
                'Clinic',
                'Hospital',
                'Private Practices',
                'Urgent Care Center',
                'Ambulatory Surgery Center',
                'Long-Term Care ',
                'Rehabilitation Center',
                'Community Health Center',
                'Home Healthcare',
                'Mental Health Center',
                'Laboratories',
                'Pharmacy',
                'Hospice Center',
                'Dialysis Center',
                'Remote/Virtual',
                'Home Care',
                'Skilled Nursing'
            ],
            'Vaccinations'=>[
                'Flu',
                'COVID',
                'HepB',
                'TDAP',
                'Varicella',
                'Measles',
                'Mumps',
                'Rubella',
                'HepC',
                'H1N1',
                'Meningococcal'
            ],
            // random values (real value needed in docs)
            'Skills'=>[
                'Peds',
                'CVICU',
                'RN',
                'Skills',
                'checklist'
            ],
            'NurseClassification'=>[
                'W-2',
                '1099/W9',
                'Volunteer',
            ],
            'PayFrequency'=>[
                'Same Day Pay',
                'Daily',
                'Weekly',
                'Every other week',
                'Bi-Monthly',
                'Monthly'
            ],
            'Benefits'=>[
                '401k',
                'Vision',
                'Dental',
                'Medical',
                'Therapy'
            ],
            'ContractTerminationPolicy' => [
                '2 weeks notice',
                '30 days notice',
                'same terms as facility',
                'no preference'
            ],
            'FacilityShiftCancellationPolicy' => [
                '1 shift/wk',
                '1 shift/2wks',
                '3 shifts/13wks',
                'no shift cancelations',
                'no preference'
            ],
            'State'=>State::all()->pluck('name')->toArray(),
            'StateCode'=>State::all()->pluck('iso2')->toArray(),
            'City'=> Cities::all()->pluck('name')->toArray(),
            'Urgency'=> [
                'Auto Offer',
                'no Auto Offer'
            ],
            'RecencyOfReference'=>[
                '3 months',
                '6 months',
                '1 year',
                '2 years',
                'More than 2 years',
            ],
            'MinTitleOfReference' => [
                'Charge',
                 'Manager'
            ]
        ];
    }
}









