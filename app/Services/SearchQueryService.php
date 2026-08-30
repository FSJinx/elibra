<?php

namespace App\Services;

class SearchQueryService
{
    /**
     * Common words that usually add little value
     * to an OPAC catalog search.
     */
    protected array $stopWords = [
        'a',
        'an',
        'and',
        'are',
        'as',
        'at',
        'be',
        'by',
        'for',
        'from',
        'how',
        'in',
        'is',
        'of',
        'on',
        'or',
        'the',
        'to',
        'what',
        'where',
        'which',
        'who',
        'with',
    ];

    /**
     * Synonyms / equivalent terms useful for library searching.
     */
    protected array $synonyms = [
        'ai' => [
            'artificial intelligence',
        ],

        'artificial intelligence' => [
            'ai',
        ],

        'ml' => [
            'machine learning',
        ],

        'machine learning' => [
            'ml',
        ],

        'programming language' => [
            'programming languages',
        ],

        'programming languages' => [
            'programming language',
        ],

        'database' => [
            'databases',
            'db',
        ],

        'databases' => [
            'database',
        ],

        'db' => [
            'database',
            'databases',
        ],
    ];

    /**
     * Process a user's search query.
     */
    public function process(string $query): array
    {
        $query = $this->normalize($query);

        $tokens = $this->tokenize($query);

        $tokens = $this->removeStopWords($tokens);

        $expandedTerms = $this->expandSynonyms($tokens);

        return [
            'original' => $query,
            'terms' => $tokens,
            'expanded_terms' => $expandedTerms,
            'search_query' => implode(' ', $expandedTerms),
        ];
    }

    /**
     * Normalize the query.
     */
    protected function normalize(string $query): string
    {
        $query = strtolower($query);

        $query = preg_replace('/[^\p{L}\p{N}\s-]/u', ' ', $query);

        $query = preg_replace('/\s+/', ' ', $query);

        return trim($query);
    }

    /**
     * Break query into individual words.
     */
    protected function tokenize(string $query): array
    {
        return array_values(
            array_filter(
                preg_split('/\s+/', $query)
            )
        );
    }

    /**
     * Remove common stop words.
     */
    protected function removeStopWords(array $tokens): array
    {
        return array_values(
            array_filter(
                $tokens,
                fn ($token) => !in_array($token, $this->stopWords, true)
            )
        );
    }

    /**
     * Expand known synonyms.
     */
    protected function expandSynonyms(array $tokens): array
    {
        $expanded = $tokens;

        foreach ($this->synonyms as $term => $synonyms) {
            $termTokens = preg_split('/\s+/', $term);

            if ($this->containsPhrase($tokens, $termTokens)) {
                $expanded = array_merge($expanded, $synonyms);
            }
        }

        return array_values(array_unique($expanded));
    }

    /**
     * Check whether a multi-word phrase exists in the token list.
     */
    protected function containsPhrase(array $tokens, array $phrase): bool
    {
        $phraseLength = count($phrase);
        $tokenCount = count($tokens);

        if ($phraseLength > $tokenCount) {
            return false;
        }

        for ($i = 0; $i <= $tokenCount - $phraseLength; $i++) {
            if (array_slice($tokens, $i, $phraseLength) === $phrase) {
                return true;
            }
        }

        return false;
    }
}

