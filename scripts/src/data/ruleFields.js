const ruleFields = [
    {
        fieldID: "job_id",
        displayName: "Org Job Id",
        workerFieldIdMatch: "",
        publishDisabled: false,
        applyDisabled: true
    },
    {
        fieldID: "job_type",
        workerFieldIdMatch: "worker_job_type",
        displayName: "Type",
        publishDisabled: true,
        applyDisabled: false
    },
    {
        fieldID: "terms",
        workerFieldIdMatch: "terms",
        displayName: "Terms",
        publishDisabled: true,
        applyDisabled: false
    },
    
    {
        fieldID: "profession",
        workerFieldIdMatch: "profession",
        displayName: "Profession",
        publishDisabled: true,
        applyDisabled: false
    },
    
    {
        fieldID: "preferred_specialty",
        workerFieldIdMatch: "specialty",
        displayName: "Specialty",
        publishDisabled: true,
        applyDisabled: false
    },
    {
        fieldID: "actual_hourly_rate",
        workerFieldIdMatch: "worker_actual_hourly_rate",
        displayName: "Actual Hourly Rate",
        publishDisabled: true,
        applyDisabled: false
    },
    {
        fieldID: "weekly_pay",
        workerFieldIdMatch: "worker_organization_weekly_amount",
        displayName: "Weekly Pay",
        publishDisabled: true,
        applyDisabled: false
    },
    
    {
        fieldID: "hours_per_week",
        workerFieldIdMatch: "worker_hours_per_week",
        displayName: "Hours Per Week",
        publishDisabled: true,
        applyDisabled: false
    },
    
    {
        fieldID: "job_state",
        workerFieldIdMatch: "worker_facility_state",
        displayName: "Job State",
        publishDisabled: true,
        applyDisabled: false
    },
    {
        fieldID: "job_city",
        workerFieldIdMatch: "worker_facility_city",
        displayName: "Job City",
        publishDisabled: true,
        applyDisabled: false
    },
    
    {
        fieldID: "preferred_shift_duration",
        workerFieldIdMatch: "worker_shift_time_of_day",
        displayName: "Shift Time",
        publishDisabled: false,
        applyDisabled: false
    },
    
    {
        fieldID: "guaranteed_hours",
        workerFieldIdMatch: "worker_guaranteed_hours",
        displayName: "Guaranteed Hrs/wk",
        publishDisabled: false,
        applyDisabled: false
    },

    {
        fieldID: "hours_shift",
        workerFieldIdMatch: "worker_hours_shift",
        displayName: "Reg Hrs/Shift",
        publishDisabled: false,
        applyDisabled: false
    },
    
    {
        fieldID: "weeks_shift",
        workerFieldIdMatch: "worker_shifts_week",
        displayName: "Shifts/Wk",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "preferred_assignment_duration",
        workerFieldIdMatch: "worker_weeks_assignment",
        displayName: "Wks/Contract",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "as_soon_as",
        workerFieldIdMatch: "worker_as_soon_as_possible",
        displayName: "As Soon As",
        publishDisabled: false,
        applyDisabled: false
    },
    
    {
        fieldID: "start_date",
        workerFieldIdMatch: "worker_start_date",
        displayName: "Start Date",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "end_date",
        workerFieldIdMatch: "worker_end_date",
        displayName: "End Date",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "rto",
        workerFieldIdMatch: "rto",
        displayName: "RTO",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "overtime",
        workerFieldIdMatch: "worker_overtime_rate",
        displayName: "Overtime",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "on_call_rate",
        workerFieldIdMatch: "worker_on_call_check",
        displayName: "On Call Rate",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "call_back_rate",
        workerFieldIdMatch: "worker_call_back_check",
        displayName: "Call Back Rate",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "orientation_rate",
        workerFieldIdMatch: "worker_orientation_rate",
        displayName: "Orientation Rate",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "weekly_taxable_amount",
        workerFieldIdMatch: "worker_weekly_taxable_amount",
        displayName: "Weekly Taxable Amount",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "weekly_non_taxable_amount",
        workerFieldIdMatch: "worker_weekly_non_taxable_amount",
        displayName: "Weekly Non Taxable Amount",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "feels_like_per_hour",
        workerFieldIdMatch: "worker_feels_like_per_hour",
        displayName: "Feels Like Per Hour",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "goodwork_weekly_amount",
        workerFieldIdMatch: "",
        displayName: "Goodwork Weekly Amount",
        publishDisabled: false,
        applyDisabled: true
    },
    {
        fieldID: "referral_bonus",
        workerFieldIdMatch: "worker_referral_bonus",
        displayName: "Referral Bonus",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "sign_on_bonus",
        workerFieldIdMatch: "worker_sign_on_bonus",
        displayName: "Sign On Bonus",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "extension_bonus",
        workerFieldIdMatch: "worker_extension_bonus",
        displayName: "Extension Bonus",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "completion_bonus",
        workerFieldIdMatch: "worker_completion_bonus",
        displayName: "Completion Bonus",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "other_bonus",
        workerFieldIdMatch: "worker_other_bonus",
        displayName: "Other Bonus",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "total_organization_amount",
        workerFieldIdMatch: "",
        displayName: "Total Organization Amount",
        publishDisabled: false,
        applyDisabled: true
    },
    {
        fieldID: "total_goodwork_amount",
        workerFieldIdMatch: "",
        displayName: "Total Goodwork Amount",
        publishDisabled: false,
        applyDisabled: true
    },
    {
        fieldID: "total_contract_amount",
        workerFieldIdMatch: "",
        displayName: "Total Contract Amount",
        publishDisabled: false,
        applyDisabled: true
    },
    {
        fieldID: "pay_frequency",
        workerFieldIdMatch: "worker_pay_frequency",
        displayName: "Pay Frequency",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "benefits",
        workerFieldIdMatch: "worker_benefits",
        displayName: "Benefits",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "clinical_setting",
        workerFieldIdMatch: "clinical_setting_you_prefer",
        displayName: "Clinical Setting",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "preferred_work_location",
        workerFieldIdMatch: "worker_preferred_work_location",
        displayName: "Preferred Work Location",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "facility_name",
        workerFieldIdMatch: "worker_facility_name",
        displayName: "Facility Name",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "facilitys_parent_system",
        workerFieldIdMatch: "worker_facilitys_parent_system",
        displayName: "Facility's Parent System",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "facility_shift_cancelation_policy",
        workerFieldIdMatch: "facility_shift_cancelation_policy",
        displayName: "Facility Shift Cancelation Policy",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "contract_termination_policy",
        workerFieldIdMatch: "contract_termination_policy",
        displayName: "Contract Termination Policy",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "traveler_distance_from_facility",
        workerFieldIdMatch: "distance_from_your_home",
        displayName: "Traveler Distance From Facility",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "job_location",
        workerFieldIdMatch: "worker_job_location",
        displayName: "Professional Licensure",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "certificate",
        workerFieldIdMatch: "certification",
        displayName: "Certificate",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "description",
        workerFieldIdMatch: "worker_description",
        displayName: "Description",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "urgency",
        workerFieldIdMatch: "worker_urgency",
        displayName: "Urgency",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "preferred_experience",
        workerFieldIdMatch: "worker_experience",
        displayName: "Preferred Experience",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "number_of_references",
        workerFieldIdMatch: "references",
        displayName: "Number Of References",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "skills",
        workerFieldIdMatch: "skills",
        displayName: "Skills",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "on_call",
        workerFieldIdMatch: "worker_on_call_check",
        displayName: "On Call",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "block_scheduling",
        workerFieldIdMatch: "block_scheduling",
        displayName: "Block Scheduling",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "float_requirement",
        workerFieldIdMatch: "float_requirement",
        displayName: "Float Requirement",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "Patient_ratio",
        workerFieldIdMatch: "worker_patient_ratio",
        displayName: "Patient Ratio",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "Emr",
        workerFieldIdMatch: "worker_emr",
        displayName: "EMR",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "Unit",
        workerFieldIdMatch: "worker_unit",
        displayName: "Unit",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "nurse_classification",
        workerFieldIdMatch: "nurse_classification",
        displayName: "Nurse Classification",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "vaccinations",
        workerFieldIdMatch: "vaccination",
        displayName: "Vaccinations",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "job_name",
        workerFieldIdMatch: "",
        displayName: "Job Name",
        publishDisabled: false,
        applyDisabled: true
    },
    {
        fieldID: "scrub_color",
        workerFieldIdMatch: "worker_scrub_color",
        displayName: "Scrub Color",
        publishDisabled: false,
        applyDisabled: false
    },
    
    {
        fieldID: "holiday",
        workerFieldIdMatch: "worker_holiday",
        displayName: "Holiday Dates",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "professional_state_licensure",
        workerFieldIdMatch: "professional_state_licensure",
        displayName: "Professional State Licensure",
        publishDisabled: false,
        applyDisabled: false
    },

];

module.exports = ruleFields;