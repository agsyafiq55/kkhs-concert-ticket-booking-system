<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lower form classes (Form 1-5)
        $lowerFormClassNames = SchoolClass::getLowerFormClassNames();
        $lowerFormLevels = ['1', '2', '3', '4', '5'];

        foreach ($lowerFormLevels as $formLevel) {
            foreach ($lowerFormClassNames as $className) {
                SchoolClass::create([
                    'form_level' => $formLevel,
                    'class_name' => $className,
                    'is_active' => true,
                ]);
            }
        }

        // Form 6 classes
        $upperFormClassNames = SchoolClass::getUpperFormClassNames();
        foreach ($upperFormClassNames as $className) {
            SchoolClass::create([
                'form_level' => '6',
                'class_name' => $className,
                'is_active' => true,
            ]);
        }

        // Peralihan classes
        $peralihanClassNames = SchoolClass::getPeralihanClassNames();
        foreach ($peralihanClassNames as $className) {
            SchoolClass::create([
                'form_level' => 'PERALIHAN',
                'class_name' => $className,
                'is_active' => true,
            ]);
        }

        $this->command->info('Successfully created all school classes:');
        $this->command->info('- Form 1-5: ' . (count($lowerFormLevels) * count($lowerFormClassNames)) . ' classes');
        $this->command->info('- Form 6: ' . count($upperFormClassNames) . ' classes');
        $this->command->info('- Peralihan: ' . count($peralihanClassNames) . ' classes');
        $this->command->info('Total: ' . SchoolClass::count() . ' classes');
    }
} 