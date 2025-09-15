<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'rrr',
        'fee_type',
        'amount',
        'invoice_file',
        'stamped_file',
        'stamped_by',
        'stamped_at',
        'user_id',
        'status',
        'comment',
    ];

    // protected $casts = [
    //     'fee_type' => 'enum:school_fees,igr',
    // ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stampedBy()
    {
        return $this->belongsTo(User::class, 'stamped_by');
    }

    protected $casts = [
        'stamped_at' => 'datetime',
    ];
}
