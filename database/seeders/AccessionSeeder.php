<?php

namespace Database\Seeders;

use App\Models\Accession;
use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AccessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $existingNumbers = Accession::withTrashed()
                ->whereNotNull('accession_number')
                ->pluck('accession_number')
                ->all();
            $nextSequences = [];
            $accessionsByItem = Accession::query()
                ->get()
                ->groupBy('item_id');

            $generateNumber = function (Item $item) use (&$existingNumbers, &$nextSequences): string {
                $categoryCode = strtoupper(trim($item->itemTypeCategory?->code ?? ''));

                if ($categoryCode === '') {
                    throw new RuntimeException(
                        "Item {$item->id} does not have an item type category with a valid code."
                    );
                }

                if ($item->library_id === null) {
                    throw new RuntimeException("Item {$item->id} does not have a library.");
                }

                $prefix = $categoryCode.str_pad((string) $item->library_id, 3, '0', STR_PAD_LEFT);

                if (! array_key_exists($prefix, $nextSequences)) {
                    $lastSequence = 0;

                    foreach ($existingNumbers as $accessionNumber) {
                        if (preg_match(
                            '/^'.preg_quote($prefix, '/').'([0-9]+)$/',
                            (string) $accessionNumber,
                            $matches
                        )) {
                            $lastSequence = max($lastSequence, (int) $matches[1]);
                        }
                    }

                    $nextSequences[$prefix] = $lastSequence + 1;
                }

                $accessionNumber = $prefix.str_pad(
                    (string) $nextSequences[$prefix],
                    3,
                    '0',
                    STR_PAD_LEFT
                );
                $nextSequences[$prefix]++;
                $existingNumbers[] = $accessionNumber;

                return $accessionNumber;
            };

            foreach (Item::query()->with('itemTypeCategory')->orderBy('id')->get() as $item) {
                $itemAccessions = $accessionsByItem->get($item->id, collect());

                if ($itemAccessions->isEmpty()) {
                    Accession::create([
                        'accession_number' => $generateNumber($item),
                        'status' => 'available',
                        'item_id' => $item->id,
                    ]);

                    continue;
                }

                foreach ($itemAccessions as $accession) {
                    if (trim((string) $accession->accession_number) === '') {
                        $accession->update([
                            'accession_number' => $generateNumber($item),
                        ]);
                    }
                }
            }
        });
    }
}
