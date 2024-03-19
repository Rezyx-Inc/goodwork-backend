<?php

use Illuminate\Database\Seeder;
use App\Models\Keyword;
use App\Models\User;

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
                'Advanced HIV/AIDS Certified Registered Nurse (AACRN)',
                'Advanced Traumatic Life Support (ATLS)',
                'Advanced Cardiac Life Support (ACLS) ',
                'Basic Life Support (BLS)',
                'Clinical Nurse Specialist; Wellness through Acute Care (Adult-Gerontology) (ACCNS-AG)',
                'Clinical Nurse Specialist; Wellness through Acute Care (Neonatal) (ACCNS-N)',
                'Clinical Nurse Specialist, Wellness through Acute Care (Pediatric) (ACCNS-P)',
                'Advanced Certified Hospice and Palliative Nurse (ACHPN)',
                'Acute Care Nurse Practitioner (ACNP-BC)',
                'Acute Care Nurse Practitioner (Adult-Gerontology) (ACNPC-AG)',
                'Adult Health Clinical Nurse Specialist (ACNS-BC)',
                'HIV/AIDS Certified Registered Nurse (ACRN)',
                'Advanced Diabetes Management (specialty certification, retired exam) (ADM-BC)',
                'Advanced Diabetes Management (ADM-BC)',
                'Certified Asthma Educator (AE-C)',
                'Forensic Nursing, Advanced (AFN-BC)',
                'Advanced Forensic Nursing (AFN-BC)',
                'Adult-Gerontology Acute Care Nurse Practitioner (AGACNP-BC)',
                'Adult-Gerontology Clinical Nurse Specialist (AGCNS-BC)',
                'Genetics Nursing, Advanced (AGN-BC)',
                'Adult-Gerontology Primary Care Nurse Practitioner (AGPCNP-BC)',
                'Advanced Holistic Nurse, Board Certified (AHN-BC)',
                'Adult Nurse Practitioner (ANP-BC)',
                'Acute Care Nurse Practitioner (Adult) (ACNPC)',
                'Advanced Oncology Certified Nurse (AOCN)',
                'Advanced Oncology Certified Nurse Practitioner (AOCNP)',
                'Advanced Oncology Certified Clinical Nurse Specialist (AOCNS)',
                'Advanced Practice Holistic Nurse (APHN-BC)',
                'Board Certified-Advanced Diabetes Management (BC-ADM)',
                'Blood & Marrow Transplant Certified Nurse (BMTCN)',
                'Electronic Fetal Monitoring (C-EFM)',
                'Neonatal Pediatric Transport (C-NPT)',
                'Certified Aesthetic Nurse Specialist (CANS)',
                'Certified Ambulatory Perianesthesia Nurse (CAPA)',
                'Certified Addictions Registered Nurse (CARN)',
                'Certified Addictions Registered Nurse - Advanced Practice (CARN-AP)',
                'Certified Breast Care Nurse (CBCN)',
                'Correctional Behavioral Health Certification (CBHC)',
                'Certified Continence Care Nurse (CCCN)',
                'Certified Continence Care Nurse-Advanced Practice (CCCN-AP)',
                'Certified Correctional Health Professional-Advanced (CCHP-A)',
                'Certified Correctional Health Professional-RN (CCHP-RN)',
                'Certified Clinical Hemodialysis Technician (CCHT)',
                'Certified Clinical Hemodialysis Technician-Advanced (CCHTA)',
                'Acute/Critical Care Clinical Nurse Specialist (Adult, Pediatric & Neonatal) (CCNS)',
                'Certified Clinical Research Associate (CCRA)',
                'Certified Clinical Research Coordinator (CCRC)',
                'Acute/Critical Care Nursing (Adult, Pediatric & Neonatal) (CCRN)',
                'Tele-ICU Acute/Critical Care Nursing (Adult) (CCRN-E)',
                'Acute/Critical Care Knowledge Professional (Adult, Pediatric & Neonatal) (CCRN-K)',
                'Certified Clinical Research Professional (CCRP)',
                'Certified in Care Coordination and Transition Management (CCTM)',
                'Certified Dialysis Licensed Practical Nurse (CD-LPN)',
                'Certified Dialysis Licensed Vocational Nurse (CD-LVN)',
                'Certified Diabetes Educator (CDE)',
                'Certified Dialysis Nurse (CDN)',
                'Certified Emergency Nurse (CEN)',
                'Certified in Executive Nursing Practice (CENP)',
                'Certified Foot Care Nurse (CFCN)',
                'Certified Flight Registered Nurse (CFRN)',
                'Certified Gastroenterology Registered Nurse (CGRN)',
                'Certified Heart Failure Nurse (CHFN)',
                'Non-Clinical HeartFailure Nurse (CHFN-K)',
                'Certified Hospice and Palliative Care Administrator (CHPCA)',
                'Certified Hospice and Palliative Licensed Nurse (CHPLN)',
                'Certified Hospice and Palliative Nurse (CHPN)',
                'Certified Hospice and Palliative Nursing Assistant (CHPNA)',
                'Certified Hospice and Palliative Pediatric Nurse (CHPPN)',
                'Certified Health Service Administrator (CHSA)',
                'Certified in Infection Control (CIC)',
                'Occupational Health Nursing Case Management (CM)',
                'Cardiac Medicine (Adult) (CMC)',
                'Certification in Managed Care Nursing (CMCN)',
                'Certified Medical-Surgical Registered Nurse (CMSRN)',
                'Certified Nurse Educator (CNE)',
                'Clinical Nurse Leader (CNL)',
                'Nurse Manager and Leader (CNML)',
                'Certified Nurse Manager and Leader (CNML)',
                'Certified Corrections Nurse (CNN)',
                'Certified Nephrology Nurse (CNN)',
                'Certified Nephrology Nurse-Nurse Practitioner (CNN-NP)',
                'Certified Corrections Nurse/Manager (CNN/M)',
                'Certified Nurse, Operating Room (CNOR)',
                'Certified Neuroscience Registered Nurse (CNRN)',
                'Clinical Nurse Specialist, Core (CNS-BC)',
                'Clinical Nurse Specialist Perioperative Certification (CNS-CP)',
                'Certified Ostomy Care Nurse (COCN)',
                'Certified Ostomy Care Nurse-Advanced Practice (COCN-AP)',
                'Certified Occupational Health Nurse (COHN)',
                'Certified Occupational Health Nurse-Specialist (COHN-S)',
                'Certified Otorhinolaryngology Nurse (CORLN)',
                'Certified Post Anesthesia Nurse (CPAN)',
                'Certified Pediatric Emergency Nurse (CPEN)',
                'Certified Pediatric Hematology Oncology Nurse (CPHON)',
                'Certified Professional in Healthcare Quality (CPHQ)',
                'Certified Professional in Healthcare Risk Management (CPHRM)',
                'Certified in Perinatal Loss Care (CPLC)',
                'Certified Pediatric Nurse (CPN)',
                'Certified Pediatric Nurse Practitioner-Primary Care (CPNP-PC)',
                'Certified Pediatric Oncology Nurse (CPON)',
                'Certified Pediatric Nurse Practitioner-Acute Care (CPP-AC)',
                'Certified Plastic Surgical Nurse (CPSN)',
                'Certified Radiologic Nurse (CRN)',
                'Certified Registered Nurse Anesthetist (CRNA)',
                'Certified Registered Nurse First Assistant (CRNFA)',
                'Certified Registered Nurse Infusion (CRNI)',
                'Certification for Registered Nurses of Opthamology (CRNO)',
                'Certified Rehabilitation Registered Nurse (CRRN)',
                'Cardiac Surgery (Adult) (CSC)',
                'Certified Transport Registered Nurse (CTRN)',
                'Urologic Associate (CUA)',
                'Certified Urologic Nurse Practitioner (CUNP)',
                'Certified Urologic Registered Nurse (CURN)',
                'Certified Wound Care Nurse (CWCN)',
                'Certified Wound Care Nurse-Advanced Practice (CWCN-AP)',
                'Certified Wound Ostomy Continence Nurse (CWOCN)',
                'Certified Wound Ostomy Continence Nurse-Advanced Practice (CWOCN-AP)',
                'Certified Wound Ostomy Nurse (CWON)',
                'Certified Wound Ostomy Nurse-Advanced Practice (CWON-AP)',
                'Dermatology Certified Nurse Practitioner (DCNP)',
                'Dermatology Nurse Certified (DNC)',
                'Emergency Nurse Practitioner (specialty certification) (ENP-BC)',
                'Family Nurse Practitioner (FNP-BC)',
                'Family Nurse Practitioner (FNP-C)',
                'Gerontological Clinical Nurse Specialist (retired exam) (GCNS-BC)',
                'Gerontological Nurse Practitioner (GNP-BC)',
                'Home Health Clinical Nurse Specialist (retired exam) (HHCNS-BC)',
                'Holistic Nurse, Board Certified (HN-BC)',
                'Holistic Baccalaureate Nurse, Board Certified (HNB-BC)',
                'Health and Wellness Nurse Coach, Board Certified (HWNC-BC)',
                'Legal Nurse Consultant Certified (LNCC)',
                'Nurse Coach, Board Certified (NC-BC)',
                'National Certified School Nurse (NCSN)',
                'Nurse Executive (NE-BC)',
                'Nurse Executive, Advanced (NEA-BC)',
                'Neonatal Nurse Practitioner (NNP-BC)',
                'Adult Nurse Practitioner (NP-C)',
                'Oncology Certified Nurse (OCN)',
                'Orthopaedic Clinical Nurse Specialist - Certified (OCNS-C)',
                'Orthopaedic Nurse Certified (ONC)',
                'Orthopaedic Nurse Practitioner-Certified (ONP-C)',
                'Progressive Care Nursing (Adult) (PCCN)',
                'Progressive Care Knowledge Professional (Adult) (PCCN-K)',
                'Pediatric Clinical Nurse Specialist (PCNS-BC)',
                'Public/Community Health Clinical Nurse Specialist (retired exam) (PHCNS-BC)',
                'Pediatric Primary Care Mental Health Specialist (PHMS)',
                'Public Health Nursing, Advanced (PHNA-BC)',
                'Adult Psychiatric-Mental Health Clinical Nurse Specialist (PMHCNS-BC)',
                'Child/Adolescent Psychiatric-Mental Health Clinical Nurse Specialist (PMHCNS-BC)',
                'Adult Psychiatric-Mental Health Nurse Practitioner (PMHNP-BC)',
                'Psychiatric-Mental Health Nurse Practitioner (across the life span) (PMHNP-BC)',
                'Pediatric Primary Care Nurse Practitioner (PPCNP-BC)',
                'Pediatric Advanced Life Support (PALS)',
                'Ambulatory Care Nursing (RN-BC)',
                'Cardiac-Vascular Nursing (RN-BC)',
                'Certified Vascular Nurse (retired exam) (RN-BC)',
                'College Health Nursing (retired exam) (RN-BC)',
                'Community Health Nursing (retired exam) (RN-BC)',
                'Faith Community Nursing (RN-BC)',
                'General Nursing, Practice (retired exam) (RN-BC)',
                'Gerontological Nursing (RN-BC)',
                'Hemostasis Nursing (RN-BC)',
                'High-Risk Perinatal Nursing (retired exam) (RN-BC)',
                'Home Health Nursing (retired exam) (RN-BC)',
                'Informatics Nursing (RN-BC)',
                'Medical-Surgical Nursing (RN-BC)',
                'Nursing Case Management (RN-BC)',
                'Nursing Professional Development (RN-BC)',
                'Pain Management Nursing (RN-BC)',
                'Pediatric Nursing (RN-BC)',
                'Perinatal Nursing (retired exam) (RN-BC)',
                'Psychiatric-Mental Health Nursing (RN-BC)',
                'Rheumatology Nursing (RN-BC)',
                'School Nursing (retired exam) (RN-BC)',
                'Low Risk Neonatal Nursing (RNC-LRN)',
                'Maternal Newborn Nursing (RNC-MNN)',
                'Neonatal Intensive Care Nursing (RNC-NIC)',
                'Inpatient Obstetric Nursing (RNC-OB)',
                'Sexual Assault Nurse Examiner - Adult/Adolescent (SANE-A)',
                'Sexual Assault Nurse Examiner - Pediatric (SANE-P)',
                'Stroke Certified Registered Nurse (SCRN)',
                'School Nurse Practitioner (retired exam) (SNP-BC)',
                'Trauma Certified Registered Nurse (TCRN)',
                'Women’s Health Care Nurse Practitioner (WHNP-BC)'
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
                'Day shift only',
                'Night shift only',
                'Open to day or night shift'
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
                'Epic',
                'Cerner'
            ]
        ];
    }
}
