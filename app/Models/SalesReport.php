<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'report_date',
        'category',
        'qty',
    ];

    protected function casts(): array
    {
        return [
            'report_date' => 'date',
            'qty' => 'integer',
        ];
    }
}
