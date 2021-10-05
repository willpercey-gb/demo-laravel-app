<?php

namespace App\Models;

use App\Models\Phonebook\Entry;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $uuid
 * @property string $variation_name
 * @property CarbonImmutable $publish_date
 */
class Phonebook extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'publish_date' => 'immutable_datetime',
    ];

    public function entries(): HasMany
    {
        return $this->hasMany(
            related:    Entry::class,
            foreignKey: 'phonebook_id',
            localKey:   'id'
        );
    }
}
