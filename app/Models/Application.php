<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    public const STATUS_PENDING = 'Pending';
    public const STATUS_APPROVED = 'Approved';
    public const STATUS_REJECTED = 'Rejected';

    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'student_id',
        'course',
        'year_level',
        'scholarship_type',
        'reason_for_applying',
        'status',
        'tally_submission_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
