<?php

namespace App\Http\Controllers;

use App\Models\ShiftCard;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    public function employees()
    {
        $currentUser = auth()->user();
        $users = User::where('role', 0)->whereNotIn('id', [$currentUser->id])->get();
        return Inertia::render('Views/AllEmployees', ['users' => $users]);
    }

    public function other_shifts($id)
    {
        $user = User::find($id);
        $cards = ShiftCard::where('employee_id', $id)->get();
        return Inertia::render('Views/OtherShiftCards', ['cards' => $cards, 'employee' => $user]);
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user -> delete();
        $currentUser = auth()->user();
        $users = User::where('role', 0)->whereNotIn('id', [$currentUser->id])->get();
        return $users;
    }
}
