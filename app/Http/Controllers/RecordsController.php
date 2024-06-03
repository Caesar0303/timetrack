<?php

namespace App\Http\Controllers;

use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecordsController extends Controller
{
    public function show($id)
    {
        $record = Record::find($id);
        return $record;
    }

    public function list()
    {
        $user = Auth::user();
        $records = Record::where('employee_id', $user->id)->get(['id', 'created_at']);
        return $records;
    }


}
