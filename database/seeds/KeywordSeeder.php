<?php

use Illuminate\Database\Seeder;
use App\Models\Keyword;
use App\Models\User;
use App\Models\State;

class KeywordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
                factory(Keyword::class)->create([
                    'created_by' => $mainSuperUserId,
                    'filter' => $key,
                    'title' => $item,
                ]);
            }
        }
    }

    private function keywordData()
    {
        return [
            'AssignmentDuration' => [
                '4 Week',
                '6 Week',
                '8 Week',
                '10 Week',
                '12 Week',
                '18 Week',
                '24 Week',
                '26 Week'
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
                'Emergency Room',
                'Medical / Surgical / Telemetry',
                'Critical Care (Adult ICU, Cardiovascular ICU, Intermediate ICU)',
                'Operating Room',
                'PACU / Recovery',
                'Cath Lab / Interventional Radiology',
                'Labor & Delivery',
                'Mother-Baby',
                'Neonatal ICU',
                'Pediatrics / Pediatrics ICU',
                'Case Management (non-bedside)',
                'Infection Prevention (non-bedside)'
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
                'Day',
                'Night',
                'Day & Night'
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
            'Profession' => [
                'Nurse Manager',
                'Nurse Educator',
                'Geriatric Care Manager',
                'Clinical Nurse Leader',
                'Nurse Researcher',
                'Public Health Nurse',
                'Travel Nurse',
                'Forensic Nurse',
                'Legal Nurse Consultant',
                'Nurse Entrepreneur',
                'Pediatric Nurse Practitioner',
                'Neonatal Nurse',
                'Critical Care Nurse',
                'Orthopedic Nurse',
                'Hospice Nurse',
                'Dialysis Nurse',
                'Occupational Health Nurse',
                'Ambulatory Care Nurse',
                'Community Health Nurse',
                'Cardiac Nurse',
            ],
            'Type'=>[
                'Clinical',
                'Non-Clinical',
            ],
            'Terms'=>[
                'Contract',
                'Perm',
                'Shift', 
                'Contract to Perm '
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
                'Home Care'
            ],
            'State'=>State::all()->pluck('name')->toArray(),
        ];
    }
}
