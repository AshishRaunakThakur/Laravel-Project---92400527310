<?php

namespace Database\Seeders;

use App\Models\CampusLocation;
use App\Models\CleaningCategory;
use App\Models\CleaningPriority;
use Illuminate\Database\Seeder;

class CampusCatalogSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name'=>'Main Library','building'=>'Academic Commons'],
            ['name'=>'Science Block · Laboratory 2','building'=>'Science Block'],
            ['name'=>'Student Centre · Ground Floor Washrooms','building'=>'Student Centre'],
            ['name'=>'North Academic Block · Room 204','building'=>'North Academic Block'],
            ['name'=>'East Wing · Main Corridor','building'=>'East Wing'],
            ['name'=>'Administration Office','building'=>'Administration Building'],
            ['name'=>'Central Quad','building'=>'Outdoor campus'],
        ] as $location) CampusLocation::firstOrCreate(['name'=>$location['name']],$location);

        foreach (['Classroom','Restroom','Laboratory','Library','Hallway','Office','Outdoor','Other'] as $name) {
            CleaningCategory::firstOrCreate(['name'=>$name]);
        }

        foreach ([
            ['name'=>'Low','description'=>'Routine cleaning; no immediate urgency.','sort_order'=>1],
            ['name'=>'Medium','description'=>'Standard priority for regular campus upkeep.','sort_order'=>2],
            ['name'=>'High','description'=>'Address soon to avoid disruption.','sort_order'=>3],
            ['name'=>'Urgent','description'=>'Requires prompt attention for safety or sanitation.','sort_order'=>4],
        ] as $priority) CleaningPriority::firstOrCreate(['name'=>$priority['name']],$priority);
    }
}
