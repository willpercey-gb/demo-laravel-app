<?php

namespace App\Models\Phonebook;

use App\Models\Phonebook;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $uuid
 * @property string $first_name
 * @property string $middle_names
 * @property string $last_name
 * @property string $email_address
 * @property string $landline_number
 * @property string $mobile_number
 * @property Phonebook $phonebook
 */
class Entry extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'phonebook_entries';
    protected $fillable = [
        'uuid',
        'first_name',
        'middle_names',
        'last_name',
        'email_address',
        'landline_number',
        'mobile_number',
    ];

    protected $hidden = [
        'deleted_at',
        'id',
        'uuid',
        'phonebook_id'
    ];

    public function phonebook(): BelongsTo
    {
        return $this->belongsTo(
            related:    Phonebook::class,
            foreignKey: 'phonebook_id',
            ownerKey:   'id'
        );
    }
}
