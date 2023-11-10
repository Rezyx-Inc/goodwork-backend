<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\UserMaster;
use App\Models\Dropdown;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UserExport implements FromCollection,WithHeadings,WithMapping,ShouldAutoSize
{
    use Exportable;

    public function collection()
    {
        return  UserMaster::where('status','<>','3')->where('type_id','2')->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Email',
            'Phone',
            'Rating',
            'Experience',
            'Diploma',
            'Compensation',
            'Country',
            'City',
            'Is verified',
            'Registered by',
            'Used Plan',
            'Status',
            'Joined at'
        ];
    }

    public function map($user): array
    {
        $drop = new Dropdown();
        $compensation = !empty($user->compensation) ? $drop->getName($user->compensation): 'N/A';
        $user_verified = $user->verified_user == '1' ? 'Yes' : 'No';
        if($user->login_type == '1'){
            $registered_by = 'Facebook';
        }elseif($user->login_type == '2'){
            $registered_by = 'Google';
        }elseif($user->login_type == '0'){
            $registered_by = 'Linkedin';
        }else{
            $registered_by = 'Email';
        }
        $plan_used = $user->is_plan_used == '1' ? 'Yes' : 'No';
        if($user->status == '0'){
            $status = 'Inactive';
        }elseif($user->status == '1'){
            $status = 'Active';
        }else{
            $status = 'Suspended';
        }
        $country = !empty($user->country) ? $user->getCountry->country_name : 'N/A';
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->phone,
            $user->rating,
            $user->experience,
            $user->diploma,
            $compensation,
            $country,
            $user->city,
            $user_verified,
            $registered_by,
            $plan_used,
            $status,
            $user->created_at
        ];
    }
}
