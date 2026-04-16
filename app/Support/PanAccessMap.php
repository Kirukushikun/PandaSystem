<?php

namespace App\Support;

class PanAccessMap
{
    public static function requestorFormDepartments(): array
    {
        return [
            70 => 'Feedmill',
            52 => 'Feedmill',
            72 => 'General Services',
            74 => 'General Services',
            75 => 'General Services',
            87 => 'General Services',
            93 => 'General Services',
            95 => 'General Services',
            67 => 'General Services',
            81 => 'Poultry',
            73 => 'Poultry',
            83 => 'Poultry',
            84 => 'Poultry',
            86 => 'Poultry',
            88 => 'Poultry',
            89 => 'Human Resources',
            90 => 'Poultry',
            91 => 'Poultry',
            92 => 'Poultry',
            56 => 'Poultry',
            26 => 'Poultry',
            97 => 'Poultry',
            98 => 'Poultry',
            11 => 'Sales & Marketing',
            35 => 'Sales & Marketing',
            77 => 'Sales & Marketing',
            85 => 'Sales & Marketing',
            6 => 'Sales & Marketing',
            37 => 'Sales & Marketing',
            9 => 'Swine',
            76 => 'Swine',
            79 => 'Swine',
            80 => 'Swine',
            82 => 'Swine',
            96 => 'Swine',
            99 => 'Swine',
            103 => 'Swine',
            71 => 'Financial Operations and Compliance',
            78 => 'Financial Operations and Compliance',
            40 => 'Financial Operations and Compliance',
            14 => 'Financial Operations and Compliance',
            39 => 'Financial Operations and Compliance',
            100 => 'Financial Operations and Compliance',
            60 => 'Human Resources',
            94 => 'IT and Security Services',
            1 => 'IT and Security Services',
            5 => 'IT and Security Services',
            24 => 'Purchasing',
            63 => 'Purchasing',
            61 => 'General Services',
        ];
    }

    public static function requestorDepartments(): array
    {
        return [
            70 => ['Feedmill'],
            52 => ['Feedmill'],
            61 => ['Human Resources', 'Poultry', 'Swine', 'Feedmill', 'General Services', 'Sales & Marketing', 'Financial Operations and Compliance', 'IT and Security Services', 'Purchasing'],
            72 => ['General Services'],
            74 => ['General Services'],
            75 => ['General Services'],
            87 => ['General Services'],
            93 => ['General Services'],
            95 => ['General Services'],
            67 => ['General Services'],
            81 => ['Poultry'],
            73 => ['Poultry'],
            83 => ['Poultry'],
            84 => ['Poultry'],
            86 => ['Poultry'],
            88 => ['Poultry'],
            89 => ['Human Resources', 'Poultry', 'Swine', 'Feedmill', 'General Services', 'Sales & Marketing', 'Financial Operations and Compliance', 'IT and Security Services', 'Purchasing'],
            90 => ['Poultry'],
            91 => ['Poultry'],
            92 => ['Poultry'],
            56 => ['Poultry'],
            26 => ['Poultry'],
            97 => ['Poultry'],
            98 => ['Poultry'],
            11 => ['Sales & Marketing'],
            35 => ['Sales & Marketing'],
            77 => ['Sales & Marketing'],
            85 => ['Sales & Marketing'],
            6 => ['Sales & Marketing'],
            37 => ['Sales & Marketing'],
            9 => ['Swine'],
            76 => ['Swine'],
            79 => ['Swine'],
            80 => ['Swine'],
            82 => ['Swine'],
            96 => ['Swine'],
            99 => ['Swine'],
            103 => ['Swine'],
            71 => ['Financial Operations and Compliance'],
            78 => ['Financial Operations and Compliance'],
            40 => ['Financial Operations and Compliance'],
            14 => ['Financial Operations and Compliance'],
            39 => ['Financial Operations and Compliance'],
            100 => ['Financial Operations and Compliance'],
            60 => ['Human Resources'],
            94 => ['IT and Security Services'],
            1 => ['IT and Security Services'],
            5 => ['IT and Security Services'],
            24 => ['Purchasing'],
            63 => ['Purchasing'],
        ];
    }

    public static function requestorDepartmentForUser(int $userId): ?string
    {
        return static::requestorFormDepartments()[$userId] ?? null;
    }

    public static function divisionHeadDepartments(): array
    {
        return [
            52 => 'Feedmill',
            67 => 'General Services',
            98 => 'Poultry',
            37 => 'Sales & Marketing',
            99 => 'Swine',
            100 => 'Financial Operations and Compliance',
            60 => 'Human Resources',
            5 => 'IT and Security Services',
            63 => 'Purchasing',
            61 => 'General Services',
        ];
    }

    public static function divisionHeadDepartmentForUser(int $userId): ?string
    {
        return static::divisionHeadDepartments()[$userId] ?? null;
    }
}
