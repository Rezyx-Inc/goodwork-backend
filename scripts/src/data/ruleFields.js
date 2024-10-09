const ruleFields = [
    {
        fieldID: "job_id",
        displayName: "Job ID",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "preferred_specialty",
        displayName: "Preferred Specialty",
        publishDisabled: true,
        applyDisabled: false
    },
    {
        fieldID: "preferred_assignment_duration",
        displayName: "Preferred Assignment Duration",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "preferred_shift_duration",
        displayName: "Preferred Shift Duration",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "preferred_work_location",
        displayName: "Preferred Work Location",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "preferred_experience",
        displayName: "Preferred Experience",
        publishDisabled: true,
        applyDisabled: false
    },
    {
        fieldID: "description",
        displayName: "Description",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "job_name",
        displayName: "Job Name",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "profession",
        displayName: "Profession",
        publishDisabled: true,
        applyDisabled: false
    },
    {
        fieldID: "job_city",
        displayName: "Job City",
        publishDisabled: true,
        applyDisabled: false
    },
    {
        fieldID: "job_state",
        displayName: "Job State",
        publishDisabled: true,
        applyDisabled: false
    },
    {
        fieldID: "job_type",
        displayName: "Job Type",
        publishDisabled: true,
        applyDisabled: false
    },
    {
        fieldID: "weekly_pay",
        displayName: "Weekly Pay",
        publishDisabled: true,
        applyDisabled: false
    },
    {
        fieldID: "start_date",
        displayName: "Start Date",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "as_soon_as",
        displayName: "As Soon As",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "hours_shift",
        displayName: "Hours Shift",
        publishDisabled: true,
        applyDisabled: false
    },
    {
        fieldID: "hours_per_week",
        displayName: "Hours Per Week",
        publishDisabled: true,
        applyDisabled: false
    },
    {
        fieldID: "specialty",
        displayName: "Specialty",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "block_scheduling",
        displayName: "Block Scheduling",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "float_requirement",
        displayName: "Float Requirement",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "facility_shift_cancelation_policy",
        displayName: "Facility Shift Cancelation Policy",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "contract_termination_policy",
        displayName: "Contract Termination Policy",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "traveler_distance_from_facility",
        displayName: "Traveler Distance From Facility",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "clinical_setting",
        displayName: "Clinical Setting",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "Patient_ratio",
        displayName: "Patient Ratio",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "Emr",
        displayName: "EMR",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "Unit",
        displayName: "Unit",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "scrub_color",
        displayName: "Scrub Color",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "rto",
        displayName: "RTO",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "guaranteed_hours",
        displayName: "Guaranteed Hours",
        publishDisabled: true,
        applyDisabled: false
    },
    {
        fieldID: "weeks_shift",
        displayName: "Weeks Shift",
        publishDisabled: true,
        applyDisabled: false
    },
    {
        fieldID: "referral_bonus",
        displayName: "Referral Bonus",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "sign_on_bonus",
        displayName: "Sign On Bonus",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "completion_bonus",
        displayName: "Completion Bonus",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "extension_bonus",
        displayName: "Extension Bonus",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "other_bonus",
        displayName: "Other Bonus",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "four_zero_one_k",
        displayName: "401K",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "health_insaurance",
        displayName: "Health Insurance",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "actual_hourly_rate",
        displayName: "Actual Hourly Rate",
        publishDisabled: true,
        applyDisabled: false
    },
    {
        fieldID: "overtime",
        displayName: "Overtime",
        publishDisabled: true,
        applyDisabled: false
    },
    {
        fieldID: "holiday",
        displayName: "Holiday",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "on_call",
        displayName: "On Call",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "on_call_rate",
        displayName: "On Call Rate",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "call_back_rate",
        displayName: "Call Back Rate",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "orientation_rate",
        displayName: "Orientation Rate",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "weekly_non_taxable_amount",
        displayName: "Weekly Non-Taxable Amount",
        publishDisabled: true,
        applyDisabled: false
    },
    {
        fieldID: "terms",
        displayName: "Terms",
        publishDisabled: true,
        applyDisabled: false
    },
    {
        fieldID: "job_location",
        displayName: "Job Location",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "vaccinations",
        displayName: "Vaccinations",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "number_of_references",
        displayName: "Number Of References",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "eligible_work_in_us",
        displayName: "Eligible To Work In US",
        publishDisabled: true,
        applyDisabled: false
    },
    {
        fieldID: "certificate",
        displayName: "Certificate",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "skills",
        displayName: "Skills",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "urgency",
        displayName: "Urgency",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "facilitys_parent_system",
        displayName: "Facility's Parent System",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "facility_name",
        displayName: "Facility Name",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "nurse_classification",
        displayName: "Nurse Classification",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "pay_frequency",
        displayName: "Pay Frequency",
        publishDisabled: true,
        applyDisabled: false
    },
    {
        fieldID: "benefits",
        displayName: "Benefits",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "feels_like_per_hour",
        displayName: "Feels Like Per Hour",
        publishDisabled: false,
        applyDisabled: false
    },
    {
        fieldID: "professional_state_licensure",
        displayName: "Professional State Licensure",
        publishDisabled: false,
        applyDisabled: false
    }
];

module.exports = ruleFields;