<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Response;
use Artisan;
use Storage;
use DB;

class DBAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin/dbadmin');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function migrate()
    {
        $result = Artisan::call('migrate');
        return(Artisan::output());
    }
    public function download_csv()
    {
        $users_table = User::all();
        $date = Carbon::now()->format('Y-m-d_H-i-s');
        $filename = "users_table_" . $date . '.csv';
        $handle = \fopen($filename, 'w+');
        fputcsv($handle, array('User ID', "User Name", "Role ID", "email", "Created At"), ';');
        foreach($users_table as $row){
            fputcsv($handle, array($row['id'], $row['name'], $row['role_id'], $row['email'], $row['created_at']), ';');
        }
        \fclose($handle);
        $headers = array(
            'Content-Type' => 'text/csv'
        );
        return Response::download($filename, $filename, $headers);
    }
    public static function check_db(){
        $db_name = DB::connection()->getDatabaseName();
        if ($db_name){
            return $db_name;
        } else {
            return false;
        }
    }
}
