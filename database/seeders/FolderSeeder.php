<?php

namespace Database\Seeders;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FolderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return;
        }

        $folderNames = [
            'Pemrograman Laravel',
            'Struktur Data & Algoritma',
            'Desain Database',
            'Keamanan Jaringan',
            'Mobile Development',
            'UI/UX Research',
            'Cloud Computing',
            'Artificial Intelligence',
            'Machine Learning',
            'Data Science'
        ];

        foreach ($users as $user) {
            // Create 3-5 folders for each user
            $numFolders = rand(3, 5);
            $userFolders = array_rand(array_flip($folderNames), $numFolders);
            
            foreach ($userFolders as $name) {
                Folder::create([
                    'user_id' => $user->id_user,
                    'name' => $name,
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
