<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_number',
        'lead_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'site_address',
        'project_title',
        'subtotal',
        'vat_rate',
        'vat_amount',
        'discount',
        'total_amount',
        'status',
        'valid_until',
        'terms_conditions',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function recalculateTotals()
    {
        $subtotal = $this->items()->sum('line_total');
        $this->subtotal = $subtotal;
        $this->vat_amount = ($subtotal - $this->discount) * ($this->vat_rate / 100);
        $this->total_amount = ($subtotal - $this->discount) + $this->vat_amount;
        $this->save();
    }
}
