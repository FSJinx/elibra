<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAcquisitionLinesRequest;
use App\Http\Requests\UpdateAcquisitionLinesRequest;
use App\Models\Acquisition;
use App\Models\AcquisitionLines;
use App\Models\Item;
use App\Services\AccessionService;
use App\Services\AcquisitionLinesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AcquisitionLinesController extends Controller
{
    public function __construct(
        protected AcquisitionLinesService $acquisitionLineService,
        protected AccessionService $accessionService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id = $request->input('id');

        $acquisitionLines = AcquisitionLines::query()
            ->with(['item:id,title,subtitle'])
            ->withCount(['accessions as quantity']);

        if ($id) {
            $acquisitionLines->where('acquisition_id', $id);
        }

        return $this->response(data: $acquisitionLines->paginate(
            null, ['*'], 'page', '1'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAcquisitionLinesRequest $request)
    {
        $acquisition = Acquisition::find($request->input('acquisition_id'));
        $item = Item::find($request->input('item_id'));

        $line = AcquisitionLines::where('acquisition_id', $acquisition->id)->where('item_id', $item->id)->first();

        if ($line) {
            return $this->response('error', 'This item is already is already added in this acquisition, edit to add new copies.', 409);
        }

        $data = array_merge($request->validated(), [
            'unit_price' => $acquisition->acquisition_mode->has_price ? $request->input('unit_price') : null,
            'quantity' => $request->input('quantity'),
        ]);

        $acquisitionLine = $this->acquisitionLineService->create($data);

        return $this->response(
            'success',
            'Item added to acquisition',
            $acquisitionLine->toArray(),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Cache::remember(
            'acquisition-lines:${id}',
            now()->addHour(),
            fn () => AcquisitionLines::with('item')->where('acquisition_id', $id)->get()
        );

        return $this->response(data: $data->toArray());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcquisitionLines $acquisitionLines)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAcquisitionLinesRequest $request, AcquisitionLines $acquisitionLine)
    {
        $acquisitionLine = $this->acquisitionLineService->update(
            $acquisitionLine,
            $request->validated()
        );

        return $this->response(
            'success',
            'Acquisition updated successfully',
            $acquisitionLine->toArray(),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcquisitionLines $acquisitionLine)
    {
        $this->authorize('delete', $acquisitionLine);

        $deleted = $this->acquisitionLineService->delete($acquisitionLine);

        if (! $deleted) {
            return $this->response('error', 'The selected item cannot be deleted from the record.', null, 500);
        }

        return $this->response('success', 'Item deleted successfully from the acquisition', null, 200);

    }
}
