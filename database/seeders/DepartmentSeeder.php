<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * One-time backfill migrating the old hardcoded App\Support\PanAccessMap
 * arrays into the departments / department_user tables.
 *
 * Deliberately a no-op if departments already exist, so an accidental
 * re-run (e.g. a deploy script that blanket-runs db:seed) can't silently
 * overwrite department/head assignments an admin has since changed via
 * the Manage Access UI. To intentionally re-apply the legacy data, empty
 * the departments/department_user tables first.
 */
class DepartmentSeeder extends Seeder
{
    public function run()
    {
        if (Department::query()->exists()) {
            $this->command?->warn('Departments already exist — skipping DepartmentSeeder (it only runs once).');
            return;
        }

        // user id => [department names the user may submit PAN requests for]
        $requestorDepartments = [
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
            13 => ['Sales & Marketing'],
        ];

        // user id => department name the user is Division Head of
        $divisionHeadDepartments = [
            52 => 'Feedmill',
            67 => 'General Services',
            98 => 'Poultry',
            37 => 'Sales & Marketing',
            99 => 'Swine',
            100 => 'Financial Operations and Compliance',
            60 => 'Human Resources',
            5 => 'IT and Security Services',
            63 => 'Purchasing',
            61 => 'Human Resources',
            13 => 'Sales & Marketing',
        ];

        $departmentNames = collect($requestorDepartments)
            ->flatten()
            ->merge($divisionHeadDepartments)
            ->unique()
            ->values();

        $departments = $departmentNames->mapWithKeys(function ($name) {
            return [$name => Department::firstOrCreate(['name' => $name])];
        });

        // user id => department name(s) they head (a department may have co-heads)
        $headsByUser = collect($divisionHeadDepartments)
            ->reduce(function ($carry, $name, $userId) {
                $carry[$userId][] = $name;
                return $carry;
            }, []);

        foreach ($requestorDepartments as $userId => $names) {
            $user = User::firstOrCreate(
                ['id' => $userId],
                ['name' => "Legacy Import #{$userId}"]
            );

            $headNames = $headsByUser[$userId] ?? [];

            $user->departmentMemberships()->syncWithoutDetaching(
                collect($names)->mapWithKeys(fn ($name) => [
                    $departments[$name]->id => [
                        'is_requestor' => true,
                        'is_head' => in_array($name, $headNames),
                    ],
                ])
            );
        }

        // Division heads not already covered by requestorDepartments (defensive; none in the legacy data today).
        foreach ($headsByUser as $userId => $names) {
            $user = User::firstOrCreate(
                ['id' => $userId],
                ['name' => "Legacy Import #{$userId}"]
            );

            $user->departmentMemberships()->syncWithoutDetaching(
                collect($names)->mapWithKeys(fn ($name) => [
                    $departments[$name]->id => ['is_head' => true],
                ])
            );
        }
    }
}
