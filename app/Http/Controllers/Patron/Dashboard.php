<?php

namespace App\Http\Controllers\Patron;

use App\Http\Controllers\Controller;
use App\Models\Circulation;
use App\Models\Holding;
use App\Models\Patron;

class Dashboard extends Controller
{
    public function index()
    {
        $user = $this->user();

        $data = [
            'active_borrowing' => $this->activeBorrowing($user->patron),
            'due_this_week' => $this->dueThisWeek($user->patron),
            'borrow_count' => $this->borrowCount($user->patron),
            'pending_reservation' => $this->pendingReservation($user->patron),
        ];

        return $this->response(data: $data);
    }

    public function activeBorrowing(Patron $patron)
    {
        return Circulation::query()->where('patron_id', $patron->id)->where('status', 'borrowed')->count('id');
    }

    public function dueThisWeek(Patron $patron)
    {
        return Circulation::query()->where('patron_id', $patron->id)->where('status', 'borrowed')->where('due_at', '>=', now()->toDateString())->count('id');
    }

    public function borrowCount(Patron $patron)
    {
        return Circulation::query()->where('patron_id', $patron->id)->count('id');
    }

    public function pendingReservation(Patron $patron)
    {
        return Holding::query()->where('reserver_id', $patron->id)->where('status', 'pending')->count('id');
    }
}
