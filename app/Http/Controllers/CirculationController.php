<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCirculationRequest;
use App\Http\Requests\UpdateCirculationRequest;
use App\Models\Accession;
use App\Models\Circulation;
use App\Models\Patron;
use App\Models\User;
use App\Services\CirculationService;
use Illuminate\Http\Request;

class CirculationController extends Controller
{
    public function __construct(
        protected CirculationService $circulationService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $circulations = Circulation::with([
            'processedBy',
            'patron',
            'accession',
            'loanMode',
            'returnReceivedBy',
        ])->get();

        return $this->response(
            'success',
            'Circulations retrieved successfully.',
            $circulations->toArray(),
            200
        );
    }

    public function preview(Request $request)
    {
        $validated = $request->validate([
            'patron' => ['required', 'string', 'max:255'],
            'accession' => ['required', 'string', 'max:255'],
        ]);

        $params = [
            'patron' => $validated['patron'],
            'accession' => trim($validated['accession']),
        ];

        $patron = User::query()
            ->where('role', 'patron')
            ->where(function ($q) use ($params) {
                $q->where('username', $params['patron'])
                    ->orWhereHas('patron', function ($p) use ($params) {
                        $p->where('ebc_number', $params['patron']);
                    });
            })
            ->select(['id', 'last_name', 'first_name', 'username', 'middle_initial', 'email'])
            ->with(['patron:id,user_id,patron_type_id,ebc_number', 'patron.patronType:id,name'])
            ->first();

        // $patron = Patron::query()
        //     ->select(['id', 'ebc_number', 'user_id', 'patron_type_id', 'program_id'])
        //     ->with([
        //         'user:id,first_name,middle_initial,last_name,username',
        //         'patronType:id,name',
        //         'program:id,name,department_id',
        //         'program.department:id,name',
        //     ])
        //     ->where(function ($query) use ($patronIdentifier) {
        //         $query->where('ebc_number', $patronIdentifier);

        //         if (ctype_digit($patronIdentifier)) {
        //             $query->orWhere('id', (int) $patronIdentifier);
        //         }

        //         $query->orWhereHas('user', function ($userQuery) use ($patronIdentifier) {
        //             $userQuery->where('username', $patronIdentifier);

        //             if (ctype_digit($patronIdentifier)) {
        //                 $userQuery->orWhere('id', (int) $patronIdentifier);
        //             }
        //         });
        //     })
        //     ->first();

        if (! $patron) {
            return $this->response('error', 'Patron not found.', null, 404);
        }

        $accession = Accession::query()
            ->select(['id', 'accession_number', 'status', 'item_id'])
            ->with(['item:id,title,subtitle,call_number'])
            ->where('accession_number', $params['accession'])
            ->first();

        if (! $accession || ! $accession->item) {
            return $this->response('error', 'Accession or catalog item not found.', null, 404);
        }

        return $this->response(
            'success',
            'Patron and item found.',
            [
                'patron' => $patron,
                'accession' => $accession,
            ],
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCirculationRequest $request)
    {
        $circulation = $this->circulationService->create($request->validated());

        return $this->response(
            'success',
            'Circulation created successfully.',
            $circulation->toArray(),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Circulation $circulation)
    {
        $circulation->load([
            'processedBy',
            'patron',
            'accession',
            'loanMode',
            'returnReceivedBy',
        ]);

        return $this->response(
            'success',
            'Circulation retrieved successfully.',
            $circulation->toArray(),
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCirculationRequest $request, Circulation $circulation)
    {
        $updated = $this->circulationService->update($circulation, $request->validated());

        return $this->response(
            'success',
            'Circulation updated successfully.',
            $updated->toArray(),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Circulation $circulation)
    {
        $deleted = $this->circulationService->delete($circulation);

        if (! $deleted) {
            return $this->response(
                'error',
                'The selected circulation record could not be deleted.',
                null,
                500
            );
        }

        return $this->response(
            'success',
            'Circulation record deleted successfully.',
            null,
            200
        );
    }
}
