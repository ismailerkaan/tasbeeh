<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class ContentVersion extends Model
{
    /**
     * @var list<string>
     */
    public const MODULES = [
        'zikir',
        'dua',
        'prayer_times',
    ];

    /**
     * @var array<string, string>
     */
    public const MODULE_COLUMN_MAP = [
        'zikir' => 'zikir_version',
        'dua' => 'dua_version',
        'prayer_times' => 'prayer_times_version',
    ];

    protected $table = 'content_versions';

    protected $fillable = [
        'id',
        'zikir_version',
        'dua_version',
        'prayer_times_version',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'zikir_version' => 'integer',
            'dua_version' => 'integer',
            'prayer_times_version' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public static function current(): self
    {
        /** @var self $contentVersion */
        $contentVersion = static::query()->firstOrCreate(
            ['id' => 1],
            [
                'zikir_version' => 1,
                'dua_version' => 1,
                'prayer_times_version' => 1,
            ]
        );

        return $contentVersion;
    }

    public static function columnForModule(string $module): string
    {
        $column = self::MODULE_COLUMN_MAP[$module] ?? null;

        if ($column === null) {
            throw new InvalidArgumentException("Unsupported module [{$module}].");
        }

        return $column;
    }

    public function bump(string $module): void
    {
        $column = self::columnForModule($module);

        $this->{$column}++;
        $this->save();
    }
}
