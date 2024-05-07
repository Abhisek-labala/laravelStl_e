<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegForm extends Model
{
    protected $table = 'regforms'; // Specify the table name if it's different from the model name

    // Define the fillable fields
    protected $fillable = [
        'name',
        'username',
        'password',
        'email',
        'phone',
        'address',
        'gender',
        'dob',
        'country', // Assuming 'country' is the foreign key for the 'countries' table
        'state',   // Assuming 'state' is the foreign key for the 'states' table
        'hobbies',
        'image_url'
    ];

    // Define relationships
    public function country()
    {
        return $this->belongsTo(Country::class, 'country');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state');
    }
}
