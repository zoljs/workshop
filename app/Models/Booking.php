<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    class Booking extends Model
    {
        protected $fillable = [
            'user_id',
            'session_id',
            'headcount',
            'payment_ref',
            'amount_paid',
            'status',
        ];

        protected $casts = [
            'headcount' => 'integer',
            'amount_paid' => 'integer',
            'session_id' => 'integer',
            'user_id' => 'integer',
        ];

        public function user(): BelongsTo
        {
            return $this->belongsTo(User::class);
        }

        public function session(): BelongsTo
        {
            return $this->belongsTo(WorkshopSession::class, 'session_id');
        }

    }
