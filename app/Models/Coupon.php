<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    class Coupon extends Model
    {
        protected $fillable = [
            'user_id',
            'code',
            'discount_flat',
            'discount_pct',
            'used',
        ];

        public function user(): BelongsTo
        {
            return $this->belongsTo(User::class);
        }
    }
