<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    class WorkshopSession extends Model
    {
        use HasFactory;

        protected $table = 'workshop_sessions';

        protected $fillable = [
            'workshop_id',
            'starts_at',
            'max_capacity',
        ];

        protected $casts = [
            'starts_at' => 'datetime',
        ];

        public function workshop(): BelongsTo
        {
            return $this->belongsTo(Workshop::class);
        }

        public function bookings(): HasMany
        {
            return $this->hasMany(Booking::class, 'session_id');
        }

        public function spotsRemaining(): int
        {
            $sum = $this->bookings()->sum('headcount');
            return $this->max_capacity - $sum;
        }
    }
