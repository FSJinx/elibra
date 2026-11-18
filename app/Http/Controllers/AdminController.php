<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\Accession;
use App\Models\Campus;
use App\Models\Circulation;
use App\Models\Holding;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Librarian;
use App\Models\Library;
use App\Models\Patron;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{

    public function store(StoreAdminRequest $request)
    {
       DB::beginTransaction();
       try{
            $data = $request->validated();

            $data['password'] = Hash::make($data['password']);

            $admin = User::create($data);

            // UserService::adminPermissions($admin);

            DB::commit();

            return $this->response(
                'success',
                'Admin successfully created.',
                $admin->toArray(),
                201,
            );

       } catch (Exception $e) {
            DB::rollback();

            return $this->response(
                'error',
                'Failed to create admin.',
                [],
                422,
            );
       }
    }

    public function update(UpdateAdminRequest $request, User $admin)
    {
        DB::beginTransaction();
        try{
            $data = $request->validated();

            //hash password if its included from the field
            if(isset($data['password'])){
                $data['password'] = Hash::make($data['password']);
            }

            $admin->update($data);

            DB::commit();

            return $this->response(
                'success',
                'Admin profile successfully updated',
                $admin->toArray(),
                200,
            );

        }catch(Exception $e){
            DB::rollback();

            return $this->response(
                'error',
                'Failed to update admin profile',
                [], 200,
            );
        }
    }

    public function destroy(User $admin)
    {
        $this->authorize('delete', $admin);

        DB::beginTransaction();
        try{
            $admin->delete();   

            DB::commit();

            return $this->response(
                'success',
                'Admin successfully deleted.',
                [],
                200
            );
        }catch(Exception $e){
            DB::rollBack();

            return $this->response(
                'error',
                'Failed to delete admin.',
                [],
                422
            );
        }
    }

    public function dashboard()
    {
        $today = now()->toDateString();

        $dailyActivity = [];
        for ($offset = 29; $offset >= 0; $offset--) {
            $date = now()->subDays($offset)->toDateString();
            $dailyActivity[] = [
                'date' => $date,
                'label' => now()->subDays($offset)->format('M j'),
                'borrowed' => Circulation::whereDate('borrowed_at', $date)->count(),
                'returned' => Circulation::whereDate('returned_at', $date)->count(),
                'overdue' => Circulation::whereIn('status', ['borrowed', 'overdue'])
                    ->whereDate('due_at', $date)
                    ->count(),
            ];
        }

        $roleBreakdown = User::query()
            ->select('role', DB::raw('COUNT(*) as total'))
            ->groupBy('role')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => ['role' => $row->role, 'total' => (int) $row->total])
            ->values();

        $campusBreakdown = Campus::query()
            ->withCount(['libraries', 'users', 'items'])
            ->orderByDesc('libraries_count')
            ->get()
            ->map(fn (Campus $campus) => [
                'id' => $campus->id,
                'name' => $campus->name,
                'code' => $campus->code,
                'libraries' => (int) $campus->libraries_count,
                'users' => (int) $campus->users_count,
                'items' => (int) $campus->items_count,
            ]);

        $libraryBreakdown = Library::query()
            ->with(['campus:id,name,code'])
            ->withCount(['items', 'librarian'])
            ->orderByDesc('items_count')
            ->limit(12)
            ->get()
            ->map(fn (Library $library) => [
                'id' => $library->id,
                'name' => $library->name,
                'campus' => $library->campus?->name,
                'items' => (int) $library->items_count,
                'staff' => (int) $library->librarian_count,
            ]);

        $itemTypeBreakdown = Item::query()
            ->join('item_types', 'items.item_type_id', '=', 'item_types.id')
            ->select('item_types.name', DB::raw('COUNT(items.id) as total'))
            ->groupBy('item_types.id', 'item_types.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn ($row) => ['name' => $row->name, 'total' => (int) $row->total])
            ->values();

        $patronTypeBreakdown = Patron::query()
            ->join('patron_types', 'patrons.patron_type_id', '=', 'patron_types.id')
            ->select('patron_types.name', DB::raw('COUNT(patrons.id) as total'))
            ->groupBy('patron_types.id', 'patron_types.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn ($row) => ['name' => $row->name, 'total' => (int) $row->total])
            ->values();

        $circulationStatusBreakdown = Circulation::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => ['status' => $row->status, 'total' => (int) $row->total])
            ->values();

        $accessionStatusBreakdown = Accession::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => ['status' => $row->status, 'total' => (int) $row->total])
            ->values();

        $holdingStatusBreakdown = Holding::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => ['status' => $row->status, 'total' => (int) $row->total])
            ->values();

        $recentCirculations = Circulation::query()
            ->with([
                'patron.user:id,last_name,first_name,middle_initial',
                'patron.patronType:id,name',
                'accession.item:id,title,call_number,library_id',
                'accession.item.library:id,name',
                'loanMode:id,name',
            ])
            ->latest('borrowed_at')
            ->limit(10)
            ->get()
            ->map(fn (Circulation $loan) => [
                'id' => $loan->id,
                'status' => $loan->status,
                'borrowed_at' => $loan->borrowed_at,
                'due_at' => $loan->due_at,
                'returned_at' => $loan->returned_at,
                'borrower' => $loan->patron?->user
                    ? trim(($loan->patron->user->last_name ?? '') . ', ' . ($loan->patron->user->first_name ?? ''))
                    : 'Unknown patron',
                'patron_type' => $loan->patron?->patronType?->name,
                'title' => $loan->accession?->item?->title,
                'call_number' => $loan->accession?->item?->call_number,
                'library' => $loan->accession?->item?->library?->name,
                'loan_mode' => $loan->loanMode?->name,
            ]);

        $recentItems = Item::query()
            ->with(['itemType:id,name', 'library:id,name,campus_id', 'library.campus:id,name'])
            ->latest('created_at')
            ->limit(10)
            ->get()
            ->map(fn (Item $item) => [
                'id' => $item->id,
                'title' => $item->title,
                'call_number' => $item->call_number,
                'publication_year' => $item->publication_year,
                'item_type' => $item->itemType?->name,
                'library' => $item->library?->name,
                'campus' => $item->library?->campus?->name,
                'created_at' => $item->created_at,
            ]);

        $data = [
            'metrics' => [
                'campuses' => Campus::count(),
                'libraries' => Library::count(),
                'librarians' => Librarian::count(),
                'patrons' => Patron::count(),
                'users' => User::count(),
                'catalog_items' => Item::count(),
                'copies' => Accession::count(),
                'available_copies' => Accession::where('status', 'available')->count(),
                'borrowed_copies' => Accession::where('status', 'borrowed')->count(),
                'active_loans' => Circulation::whereIn('status', ['borrowed', 'overdue'])->count(),
                'overdue_loans' => Circulation::whereIn('status', ['borrowed', 'overdue'])->where('due_at', '<', now())->count(),
                'borrowed_today' => Circulation::whereDate('borrowed_at', $today)->count(),
                'returned_today' => Circulation::whereDate('returned_at', $today)->count(),
                'total_circulations' => Circulation::count(),
                'renewals' => (int) Circulation::sum('renewal_count'),
                'fine_balance' => (float) Circulation::sum('fine_charged'),
                'pending_holdings' => Holding::where('status', 'pending')->count(),
                'active_inventories' => Inventory::where('status', 'pending')->count(),
            ],
            'daily_activity' => $dailyActivity,
            'role_breakdown' => $roleBreakdown,
            'campus_breakdown' => $campusBreakdown,
            'library_breakdown' => $libraryBreakdown,
            'item_types' => $itemTypeBreakdown,
            'patron_types' => $patronTypeBreakdown,
            'circulation_statuses' => $circulationStatusBreakdown,
            'accession_statuses' => $accessionStatusBreakdown,
            'holding_statuses' => $holdingStatusBreakdown,
            'recent_circulations' => $recentCirculations,
            'recent_items' => $recentItems,
        ];

        return $this->response(data: $data);
    }
}
