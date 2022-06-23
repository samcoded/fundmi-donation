<?php

namespace App\Models;

use App\Models\Donor;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'target',
        'user_id',
        'tags',
        'status',
        'amount',
    ];

    public function scopeFilter($query, array $filters)
    {
        if ($filters['tag'] ?? false) {
            $query->where('tags', 'like', '%' . request('tag') . '%');
        }

        if ($filters['search'] ?? false) {
            $query->where('title', 'like', '%' . request('search') . '%')
                ->orWhere('description', 'like', '%' . request('search') . '%')
                ->orWhere('tags', 'like', '%' . request('search') . '%');
        }
    }

    // Relationship To User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function donors()
    {
        return $this->hasMany(Donor::class, 'donation_listing_id');
    }
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class, 'donation_listing_id');
    }
}