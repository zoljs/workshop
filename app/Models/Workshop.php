<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    class Workshop extends Model
    {
        protected $fillable = [
            'user_id',
            'name',
            'description',
            'duration_minutes',
            'price_per_person',
            'archived',
        ];

        public function instructor(): BelongsTo
        {
            return $this->belongsTo(User::class, 'user_id');
        }

        public function sessions(): HasMany
        {
            return $this->hasMany(WorkshopSession::class);
        }
    }
