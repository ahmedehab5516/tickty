<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',        // Company the subscription is tied to
        'plan_type',         // Plan type (e.g., Basic, Premium)
        'subscription_start',// Start date
        'subscription_end',  // End date
        'payment_status',    // Payment status (paid/pending)
        'cinema_count',      // Number of cinemas
        'active',            // Whether the subscription is active
    ];

    /**
     * Get the company associated with the subscription.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
