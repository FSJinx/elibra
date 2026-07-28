<?php

namespace App\Services;

use App\Models\Department;
use Closure;
use DateInterval;
use DateTimeInterface;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    // resources
    public const CAMPUSES = 'campuses';
    public const DEPARTMENTS = 'departments';
    public const PROGRAMS = 'programs';

    
    /**
     * Cache a query using a versioned cache key.
     * 
     * A versioned cache key is used so that all cached entries for the same
     * resource can be invalidated by simply incrementing the resource version.
     *
     * @param string $resource   Resource name (e.g. campuses, branches).
     * @param array $parameters  Query parameters used to generate a unique cache key.
     * @param DateTimeInterface|DateInterval|int $ttl Cache lifetime.
     * @param Closure $callback  Callback executed when the cache is missing.
     */
    public static function remember(
        string $resource,
        array $parameters,
        DateTimeInterface|DateInterval|int $ttl,
        Closure $callback
    ){
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