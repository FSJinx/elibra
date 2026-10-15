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
            $existingAccessions = Accession::query()
                ->get(['item_id', 'accession_number']);
            $itemsWithAccessions = $existingAccessions
                ->pluck('item_id')
                ->all();
            $nextSequences = [];

            foreach (Item::query()->with('itemTypeCategory')->orderBy('id')->get() as $item) {
                if (in_array($item->id, $itemsWithAccessions, true)) {
                    continue;
                }

                $categoryCode = strtoupper(trim($item->itemTypeCategory?->code ?? ''));

                if ($categoryCode === '') {
                    throw new RuntimeException(
                        "Item {$item->id} does not have an item type category with a valid code."
                    );
                }

                $prefix = $categoryCode.str_pad((string) $item->library_id, 3, '0', STR_PAD_LEFT);

                if (! array_key_exists($prefix, $nextSequences)) {
                    $lastSequence = 0;

                    foreach ($existingAccessions as $accession) {
                        if (preg_match(
                            '/^'.preg_quote($prefix, '/').'([0-9]+)$/',
                            (string) $accession->accession_number,
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

                Accession::create([
                    'accession_number' => $accessionNumber,
                    'status' => 'available',
                    'item_id' => $item->id,
                ]);

                $nextSequences[$prefix]++;
            }
        });
    }
}
