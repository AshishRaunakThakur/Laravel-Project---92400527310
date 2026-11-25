<?php

namespace Database\Seeders;

use App\Models\CampusLocation;
use App\Models\CleaningCategory;
use App\Models\CleaningRecord;
use App\Models\CleaningPriority;
use Illuminate\Database\Seeder;

class DemoCleaningDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CampusCatalogSeeder::class);
        $rows=[
            ['location'=>'Main Library','category'=>'Library','description'=>'[DEMO] Wipe shared tables, vacuum the reading hall and empty recycling bins.','priority'=>'Medium','status'=>'Pending','days'=>1],
            ['location'=>'North Academic Block · Room 204','category'=>'Classroom','description'=>'[DEMO] Sanitize desks and clean the whiteboard after class.','priority'=>'High','status'=>'In Progress','days'=>0],
            ['location'=>'Student Centre · Ground Floor Washrooms','category'=>'Restroom','description'=>'[DEMO] Restock soap and paper supplies, then disinfect fixtures.','priority'=>'Urgent','status'=>'Pending','days'=>2],
            ['location'=>'East Wing · Main Corridor','category'=>'Hallway','description'=>'[DEMO] Sweep and mop the corridor; clean the entrance glass.','priority'=>'Low','status'=>'Completed','days'=>-1],
        ];
        foreach($rows as $row) {
            $location=CampusLocation::where('name',$row['location'])->firstOrFail();
            $category=CleaningCategory::where('name',$row['category'])->firstOrFail();
            $priority=CleaningPriority::where('name',$row['priority'])->firstOrFail();
            CleaningRecord::firstOrCreate(['description'=>$row['description']], [
                'location_id'=>$location->id,'category_id'=>$category->id,'priority_id'=>$priority->id,'location'=>$location->name,'category'=>$category->name,
                'description'=>$row['description'],'priority'=>$row['priority'],'assigned_staff'=>'DEMO · Unassigned',
                'status'=>$row['status'],'scheduled_date'=>today()->addDays($row['days']),
                'completed_at'=>$row['status']==='Completed'?now():null,
            ]);
        }
    }
}
