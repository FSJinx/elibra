<?php

namespace Database\Seeders;

use App\Models\Authorship;
use App\Models\ItemType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AuthorshipSeeder extends Seeder
{
    protected const authorships = [
        [
            'itemType' => 'book',
            'authorship' => [
                'Author',
                'Co-author',
                'Editor',
                'Compiler',
                'Translator',
                'Illustrator',
                'Photographer',
                'Foreword Author',
                'Introduction Author',
                'Contributor',
            ],
        ],

        [
            'itemType' => 'academic',
            'authorship' => [
                'Researcher',
                'Lead Researcher',
                'Co-researcher',
                'Adviser',
                'Sub-adviser',
                'Principal Investigator',
                'Co-investigator',
                'Thesis Author',
                'Dissertation Author',
                'Editor',
                'Reviewer',
                'Consultant',
            ],
        ],

        [
            'itemType' => 'serial',
            'authorship' => [
                'Author',
                'Editor',
                'Chief Editor',
                'Managing Editor',
                'Associate Editor',
                'Contributing Editor',
                'Columnist',
                'Reporter',
                'Correspondent',
                'Compiler',
                'Translator',
                'Publisher',
            ],
        ],

        [
            'itemType' => 'multimedia',
            'authorship' => [
                'Director',
                'Producer',
                'Executive Producer',
                'Writer',
                'Screenwriter',
                'Editor',
                'Narrator',
                'Presenter',
                'Composer',
                'Performer',
                'Photographer',
                'Videographer',
                'Animator',
                'Illustrator',
                'Translator',
            ],
        ],
    ];

    public function run(): void
    {
        foreach (self::authorships as $itemTypeData) {

            $itemType = ItemType::where('name', $itemTypeData['itemType'])->first();

            if (! $itemType) {
                continue;
            }

            foreach ($itemTypeData['authorship'] as $slug => $name) {

                Authorship::create([
                    'item_type_id' => $itemType->id,
                    'slug' => Str::lower($itemTypeData['itemType'].'_'.str_replace(' ', '_', $name)),
                    'name' => $name,
                ]);
            }
        }
    }
}
