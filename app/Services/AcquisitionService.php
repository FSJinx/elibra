<?php

namespace App\Services;

use App\Models\Acquisition;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AcquisitionService
{
    protected function fields(): array
    {
        return (new Acquisition)->getFillable();
    }

    public function index(array $filters)
    {
        return CacheService::remember(
            CacheService::ACQUISITIONS,
            $filters,
            now()->addMinutes(10),
            function () use ($filters) {
                $query = Acquisition::query()->with(['receiver', 'acquisitionMode', 'acquisitionRequest'])
                    ->when($filters['search'] ?? null, fn ($q, $search) => $q->where('reference', 'like', "%{$search}%")->orWhere('dealer', 'like', "%{$search}%"))
                    ->when($filters['acquisition_mode_id' ?? null], fn ($q, $a) => $q->where('acquisition_mode_id', $a));

                $allowedSorts = [
                    'id',
                    'reference',
                    'dealer',
                    'acquisition_mode_id',
                    'acquisition_date',
                    'created_at',
                    'updated_at',
                ];

                $sort = $filters['sort'];

                if (! in_array($sort, $allowedSorts)) {
                    $sort = 'id';
                }

                $order = in_array($filters['order'], ['asc', 'desc'])
                    ? $filters['order']
                    : 'asc';

                return $query
                    ->orderBy($sort, $order)
                    ->paginate(
                        $filters['per_page'],
                        ['*'],
                        'page',
                        $filters['page']
                    );
            }
        );
    }

    public function generateReference(Acquisition $acquisition)
    {
        $campusCode = $acquisition->receiver->library?->campus?->code;
        $date = $acquisition->created_at->format('Ymd');

        // Get the number of acquisitions for this campus on this date
        $sequence = Acquisition::whereHas('receiver.library.campus', function ($query) use ($campusCode) {
            $query->where('code', $campusCode);
        })
            ->whereDate('created_at', $acquisition->created_at)
            ->count();

        return "ACQ{$campusCode}-{$date}{$sequence}";
    }

    public function create(array $data): Acquisition
    {
        $acquisition = DB::transaction(function () use ($data) {
            $acquisition = Acquisition::create(Arr::only($data, self::fields()));

            $acquisition->reference = $this->generateReference($acquisition);
            $acquisition->save();

            return $acquisition->fresh();
        });

        CacheService::invalidate(CacheService::ACQUISITIONS);

        return $acquisition->load([
            'receiver',
        ]);
    }

    public function update(Acquisition $acquisition, array $data): Acquisition
    {
        $acquisition = DB::transaction(function () use (&$acquisition, &$data) {
            $acquisition->update(
                Arr::only($data, [
                    'reference',
                    'dealer',
                    'acquisition_mode',
                    'acquisition_date',
                    'remarks',

                    'receiver_id',
                    'acquisition_request_id',
                ])
            );

            return $acquisition->fresh([
                'receiver',
                'acquisitionRequest',
            ]);
        });

        CacheService::invalidate(CacheService::ACQUISITIONS);

        return $acquisition;
    }

    public function delete(Acquisition $acquisition): bool
    {
        $deleted = DB::transaction(function () use ($acquisition) {

            $acquisition->delete();

            return true;
        });

        if ($deleted) {
            CacheService::invalidate(CacheService::ACQUISITIONS);
        }

        return $deleted;
    }
}
