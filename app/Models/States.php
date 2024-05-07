<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class States extends Model
{
    protected $table = 'states'; // Specify the table name if it's different from the model name

    // Define the fillable fields
    protected $fillable = [
        'sid',
        'state_name',
        'country_id'
    ];

    // Define relationships
    public function country()
    {
        return $this->belongsTo(Country::class, 'country');
    }
}
