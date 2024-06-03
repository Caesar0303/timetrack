<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\ShiftCard;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function store_employee()
    {
        if (\request()->admin === true) {
            $role = 1;
        } else {
            $role = 0;
        }

        $user = User::create([
            'name' => \request()->name,
            'email' => \request()->email,
            'password' => Hash::make(\request()->password),
            'role' => $role,
        ]);

        return 200;
    }
    public function add_employee()
    {
        return Inertia::render('Views/AddEmployee');
    }

    public function admin_page()
    {
        $users = User::where('role', 0)->get();
        return Inertia::render('Views/AdminEmployees', ['users' => $users, 'admin' => true]);
    }

    public function employee_edit($id)
    {
        $user = User::find($id);
        return $user;
    }

    public function employee_update($id)
    {
        $user = User::find($id);
        $user -> update([
            'name' => \request()->name,
            'email' => \request()->email,
        ]);

        if (\request()->password !== null) {
            $user->update([
                'password' => Hash::make(\request()->password),
            ]);
        }

        $users = User::where('role', 0)->get()->toArray();
        $users = array_map(function($user) {
            unset($user['password']);
            return $user;
        }, $users);
        return $users;
    }

    public function show_employee($id)
    {
        $user = User::find($id);
        $cards = ShiftCard::where('employee_id', $id)->get();

        $dayOfWeek = Carbon::now()->dayOfWeek;

        $dayMap = [
            0 => 'sunday',
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
        ];

        $todayColumn = $dayMap[$dayOfWeek];

        $shiftCard = ShiftCard::where('employee_id', $id)
            ->where($todayColumn, '!=', 0)
            ->first();

        if ($shiftCard === null) {
            return Inertia::render('Views/AdminShowEmployee', ['cards' => $cards, 'access' => false,'user' => $user]);
        }
        return Inertia::render('Views/AdminShowEmployee', ['cards' => $cards, 'access' => true,'user' => $user]);
    }

    public function list($id)
    {
        $records = Record::where('employee_id', $id)->get(['id', 'created_at']);
        return $records;
    }
}
