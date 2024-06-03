<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\ShiftCard;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ShiftCardController extends Controller
{
    public function index()
    {
        $user_id = auth()->id();
        $cards = ShiftCard::where('employee_id', $user_id)->get();
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin_page');
        } else {
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

            $shiftCard = ShiftCard::where('employee_id', $user_id)
                ->where($todayColumn, '!=', 0)
                ->first();

            if ($shiftCard === null) {
                return Inertia::render('Dashboard', ['cards' => $cards, 'access' => false]);
            }
            return Inertia::render('Dashboard', ['cards' => $cards, 'access' => true]);
        }
    }

    public function my_cards()
    {

        $user_id = auth()->id();
        $cards = ShiftCard::where('employee_id', $user_id)->get();
        return Inertia::render('Views/MyShiftCards', ['cards' => $cards]);
    }

    public function start_shift($id)
    {
        $card = ShiftCard::find($id);
        $end_time = $card->end_time;

        $current_time = Carbon::now()->addHours(5);

        if ($current_time->greaterThan($end_time)) {
            return response()->json(['error' => 'Текущее время позднее времени окончания смены.'], 400);
        }

        $card->update([
            'start_of_shift_time' => Carbon::now()->addHours(5),
            'status' => 1,
        ]);

        $user_id = auth()->id();
        $cards = ShiftCard::where('employee_id', $user_id)->get();
        return $cards;
    }

    public function end_shift($id)
    {
        $card = ShiftCard::find($id);
        $end = Carbon::now()->addHours(5);
        $card->update([
            'end_of_shift_time' => $end,
            'status' => 2,
        ]);

        $record = Record::create([
            'employee_id' => $card->employee_id,
            'start_time' => $card->start_time,
            'start_of_shift_time' => $card->start_of_shift_time,
            'end_time' => $card->end_time,
            'end_of_shift_time' => $card->end_of_shift_time,
            'status' => $card->status,
        ]);

        $start = Carbon::parse($card->start_of_shift_time);

        $diff = $start->diff($end);

        $hours = $diff->h + ($diff->days * 24);

        $user_id = auth()->id();
        $user = User::find($user_id);

        $user->update([
            'ended_shifts' => $user->ended_shifts + 1,
            'work_hours' => $user->work_hours + $hours,
        ]);

        $cards = ShiftCard::where('employee_id', $user_id)->get();
        return $cards;
    }

    public function add_shift($id)
    {
        $user = User::find($id);
        $admin_id = auth()->id();

        $card = ShiftCard::where('employee_id',$id)->first();
        if (!$card) {
            $card = ShiftCard::create([
                'employee_id' => $id,
                'admin_id' => $admin_id,
            ]);
        }
        return ['user' => $user, 'card' => $card];
    }

    public function store($id)
    {
        $admin_id = auth()->id();
        $card = ShiftCard::updateOrCreate(
            ['employee_id' => $id],
            [
                'admin_id' => $admin_id,
                'start_time' => \request()->start_time,
                'end_time' => \request()->end_time,
                'monday' => \request()->monday,
                'tuesday' => \request()->tuesday,
                'wednesday' => \request()->wednesday,
                'thursday' => \request()->thursday,
                'friday' => \request()->friday,
                'saturday' => \request()->saturday,
                'sunday' => \request()->sunday,
            ]
        );
    }

    public function delete($id,$user_id)
    {
        $card = ShiftCard::find($id);
        $card->delete();

        $user = User::find($user_id);
        $cards = ShiftCard::where('employee_id', $user_id)->get();
        return $cards;
    }
}
