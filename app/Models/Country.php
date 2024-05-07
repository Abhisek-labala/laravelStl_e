<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries'; // Specify the table name if it's different from the model name

    // Define the fillable fields
    protected $fillable = [
        'id',
        'country_name'
    ];

    // Define relationships
   

    public function state()
    {
        return $this->belongsTo(States::class, 'state');
    }
}
