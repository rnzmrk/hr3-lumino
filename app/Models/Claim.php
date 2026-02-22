<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'employee_name',
        'claim_type',
        'description',
        'amount',
        'date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    /**
     * Get the employee that owns the claim.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the formatted amount with peso sign.
     */
    public function getFormattedAmountAttribute(): string
    {
        return '₱' . number_format($this->amount, 2);
    }

    /**
     * Get the claim type label.
     */
    public function getClaimTypeLabelAttribute(): string
    {
        return [
            'travel' => 'Travel',
            'medical' => 'Medical',
            'food' => 'Food',
            'office_supplies' => 'Office Supplies',
            'training' => 'Training',
            'others' => 'Others',
        ][$this->claim_type] ?? $this->claim_type;
    }

    /**
     * Get the status label with color.
     */
    public function getStatusLabelAttribute(): array
    {
        return [
            'pending' => ['label' => 'Pending', 'color' => 'yellow'],
            'approved' => ['label' => 'Approved', 'color' => 'green'],
            'rejected' => ['label' => 'Rejected', 'color' => 'red'],
        ][$this->status] ?? ['label' => $this->status, 'color' => 'gray'];
    }

    /**
     * Scope a query to only include pending claims.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved claims.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include rejected claims.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope a query to filter by claim type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('claim_type', $type);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
}
