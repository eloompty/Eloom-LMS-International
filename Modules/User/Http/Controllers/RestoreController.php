<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RestoreController extends Controller
{
    /* Restore upload database */
    public function restore(Request $request)
    {
        // Check if the request has a file named 'database_dump'
        if ($request->has('file')) {
            $file = $request->file('file');

            // Make sure the file is an SQL database dump
            if ($file->getClientOriginalExtension() === 'sql') {

                // Store the uploaded .sql file
                $path = $request->file('file')->store('temp');

                // Get the full path of stored file
                $fullPath = Storage::path($path);

                try {
                    // Turn off foreign key checks
                    DB::statement('SET FOREIGN_KEY_CHECKS=0;');

                    // Get all table names
                    $tableNames = DB::select('SHOW TABLES');

                    // Loop through all tables and drop each table
                    foreach ($tableNames as $name) {
                        foreach ($name as $table) {
                            DB::statement('DROP TABLE IF EXISTS ' . $table . ';');
                        }
                    }

                    // Turn on foreign key checks
                    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

                    // Import backup from .sql file
                    exec('mysql -u ' . env('DB_USERNAME') . ' -p' . env('DB_PASSWORD') . ' ' . env('DB_DATABASE') . ' < ' . $fullPath);

                    // Clear cache after the operations
                    Artisan::call('cache:clear');

                    // Delete the uploaded .sql file from local storage
                    Storage::delete($path);

                    $filePath = $file->getRealPath();
                    $sql = file_get_contents($filePath);

                    DB::unprepared($sql);
                    return redirect()->back()->with('success', 'Database has been restored');
                    // return response()->json(['message' => 'Database restored successfully!']);
                } catch (\Exception $e) {
                    // If something went wrong, delete the uploaded .sql file
                    Storage::delete($path);

                    return response()->json(['message' => 'Error during the database restore: ' . $e->getMessage()], 500);
                }
            } else {
                return redirect()->back()->with('failure', 'Invalid file format. Please upload a valid SQL database dump.');
            }
        } else {
            return redirect()->back()->with('failure', 'No file selected. Please upload an SQL database dump.');
        }
    }
}
