<?php

namespace App\Http\Controllers;

use App\Models\Backup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BackupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $backups = Backup::select("id", "name", "file_path", "size", "status")
            ->paginate();

        return response()->json([
            'success' => true,
            'message' => 'Backups fetched successfully',
            'data' => [
                'backups' => $backups
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function download()
    {

        $tables = [];
        $result = DB::select("SHOW TABLES");

        foreach ($result as $item) {
            $tables[] = $item->{'Tables_in_' . env('DB_DATABASE')};
        }

        $structure = '';
        $data = '';
        foreach ($tables as $table) {
            $showTableResult = DB::select("SHOW CREATE TABLE " . $table . "");


            foreach ($showTableResult as $showTableRow) {
                $showTableRow = (array)$showTableRow;
                $structure .= "\n\n" . $showTableRow["Create Table"] . ";\n\n";
            }

            $records = DB::select("SELECT * FROM " . $table);

            foreach ($records as $record) {
                $record = (array)$record;
                $tableColumnArray = array_keys($record);
                foreach ($tableColumnArray as $key => $name) {
                    $tableColumnArray[$key] = '`' . $tableColumnArray[$key] . '`';
                }

                $tableValueArray = array_values($record);
                $data .= "\nINSERT INTO $table (";

                $data .= "" . implode(", ", $tableColumnArray) . ") VALUES \n";

                foreach ($tableValueArray as $key => $recordColumn)
                    $tableValueArray[$key] = addslashes($recordColumn);

                $data .= "('" . implode("','", $tableValueArray) . "');\n";
            }
        }

        $output = $structure . $data;

        // use headers in order to generate the download
        $headers = [
            'Content-Disposition' => sprintf('attachment; filename="%s"', date('Y-m-d') . '_backup.sql'),
        ];

        return response()->make($output, 200, $headers);
    }
}
