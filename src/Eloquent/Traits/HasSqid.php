<?php

namespace ErikSulymosi\EloquentSqids\Eloquent\Traits;

use ErikSulymosi\EloquentSqids\Eloquent\Scopes\SqidScope;
use ErikSulymosi\EloquentSqids\Facades\Sqids;
use Illuminate\Database\Eloquent\Model;

/**
 * @method Model|null findBySqid($sqid)
 * @method Model findBySqidOrFail($sqid)
 */
trait HasSqid
{
    protected static bool $useStrictSqids = false;

    public static function setStrictSqids(bool $strictSqids = true): void
    {
        static::$useStrictSqids = $strictSqids;
    }

    public static function usesStrictSqids(): bool
    {
        return static::$useStrictSqids;
    }

    public static function bootHasSqid()
    {
        static::addGlobalScope(new SqidScope);
    }

    public function sqid(): string|null
    {
        return $this->idToSqid($this->getKey());
    }

    /**
     * Decode the sqid to the id
     */
    public function sqidToId(string $sqid): int|null
    {
        $sqids = Sqids::connection($this->getSqidsConnection());
        $id = $sqids->decode($sqid)[0] ?? null;

        if (is_null($id)) {
            return null;
        }

        if (
            static::usesStrictSqids() &&
            $sqids->encode([$id]) !== $sqid
        ) {
            return null;
        }

        return $id;
    }

    /**
     * Encode an id to its equivalent sqid
     */
    public function idToSqid(int $id): string|null
    {
        return Sqids::connection($this->getSqidsConnection())
            ->encode([$id]);
    }

    protected function getSqidAttribute()
    {
        return $this->sqid();
    }

    public function getSqidsConnection()
    {
        return config('sqids.default');
    }
}
