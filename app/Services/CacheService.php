<?php

namespace App\Services;

use Closure;
use DateInterval;
use DateTimeInterface;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    // resources

    // ========= ORGANIZATIONS ==========
    public const CAMPUSES = 'campuses';

    public const DEPARTMENTS = 'departments';

    public const PROGRAMS = 'programs';

    public const LIBRARY = 'libraries';

    public const SECTIONS = 'sections';

    // ========= USERS, ACCOUNTS, AND PERMISSIONS ==========
    public const PATRONS = 'patrons';

    public const LIBRARIANS = 'librarians';

    // ========= CATALOGING ==========
    public const ITEMS = 'items';

    public const VENDORS = 'vendors';

    public const PUBLISHERS = 'publishers';

    public const AUTHORSHIPS = 'authorships';

    // ========= AUTHORITY CONTROL ==========
    public const AUTHORS = 'authors';

    public const LANGUAGES = 'languages';

    // ========= ACQUISITION AND ACCESSION ==========
    public const ACQUISITIONS = 'acquisitions';

    public const ACQUISITION_REQUESTS = 'acquisition_requests';

    public const ACQUISITION_LINES = 'acquisition_lines';

    public const ACCESSION = 'accession';

    public const SUBSCRIPTIONS = 'subscriptions';

    public const SUBSCRIPTION_CREDENTIALS = 'subscription_credentials';

    public const MEDIA = 'medias';

    // ========= CIRCULATION ==========
    public const ATTENDANCE_LOGS = 'attendance_logs';

    public const CIRCULATIONS = 'circulations';

    public const FINES_TRANSACTIONS = 'fines_transactions';

    public const PATRON_TYPE_LOAN_POLICY = 'patron_type_loan_policy';

    public const OPAC = 'opac';

    /**
     * Cache a query using a versioned cache key.
     *
     * A versioned cache key is used so that all cached entries for the same
     * resource can be invalidated by simply incrementing the resource version.
     *
     * @param  string  $resource  Resource name (e.g. campuses, branches).
     * @param  array  $parameters  Query parameters used to generate a unique cache key.
     * @param  DateTimeInterface|DateInterval|int  $ttl  Cache lifetime.
     * @param  Closure  $callback  Callback executed when the cache is missing.
     */
    public static function remember(
        string $resource,
        array $parameters,
        DateTimeInterface|DateInterval|int $ttl,
        Closure $callback
    ) {
        // To ensure that same parameters always produce the same cache key [ REGARDLESS OF ORDER ]
        ksort($parameters);

        // Cache version
        $version = Cache::get(self::versionKey($resource), 1);

        // Generate cache key
        $cacheKey = sprintf(
            '%s:v%s:%s',
            $resource,
            $version,
            md5(json_encode($parameters))
        );

        return Cache::remember($cacheKey, $ttl, $callback);

    }

    /**
     * Invalidate all cached entries for a resource.
     *
     * Instead of deleting individual cache keys, increment the resource version.
     * Future requests will generate new cache keys, causing fresh data to be cached.
     * Old cache entries will naturally expire based on their TTL.
     */
    public static function invalidate(string $resource): void
    {
        Cache::increment(self::versionKey($resource));
    }

    private static function versionKey(string $resource): string
    {
        return "{$resource}_version";
    }
}
