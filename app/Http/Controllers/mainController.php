<?php

namespace App\Http\Controllers;
use App\Models\RegForm;
use App\Models\Country;
use App\Models\States;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;




class mainController extends Controller
{
    function getData()
    {
        $data = RegForm::select(
            'regforms.id',
            'regforms.name',
            'regforms.username',
            'regforms.password',
            'regforms.email',
            'regforms.phone',
            'regforms.address',
            'regforms.gender',
            'regforms.dob',
            'countries.country_name AS country',
            'states.state_name AS state',
            'regforms.hobbies',
            'regforms.image_url'
        )
        ->join('countries', 'regforms.country', '=', 'countries.id')
        ->join('states', 'regforms.state', '=', 'states.sid')
        ->get();

    // Modify image_url to include HTML image tag
    $data->transform(function ($item) {
        $item->image_url = '<img src="/uploads/' . $item->image_url . '" width="100" height="100">';
        return $item;
    });

    // Return JSON response
    return response()->json($data);  
  }
  function insertData(Request $request)
  {
  $validatedData = $request->validate([
    'name' => 'required',
    'username' => 'required|alpha_num|unique:regforms',
    'dob' => 'required|date',
    'phone' => 'required|numeric',
    'email' => 'required|email',
    'address' => 'required',
    'gender' => 'required',
    'country' => 'required',
    'state' => 'required',
    'hobbies' => 'required',
    'fileToUpload' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max size
]);

// Handle file upload
if ($request->hasFile('fileToUpload')) {
    $file = $request->file('fileToUpload');
    $new_name = rand() . '.' . $file->getClientOriginalExtension();
    $file->move(public_path('uploads'), $new_name);
} else {
    return response()->json(['error' => 'No file uploaded.'], 400);
}

// Create a new RegForm instance
$regForm = new RegForm();
$regForm->name = $validatedData['name'];
$regForm->username = $validatedData['username'];
$regForm->password = Hash::make('password'); // Default password 'password'
$regForm->dob = $validatedData['dob'];
$regForm->phone = $validatedData['phone'];
$regForm->email = $validatedData['email'];
$regForm->address = $validatedData['address'];
$regForm->gender = $validatedData['gender'];
$regForm->country = $validatedData['country'];
$regForm->state = $validatedData['state'];
$regForm->hobbies = $validatedData['hobbies'];
$regForm->image_url = $new_name;

// Save the regForm
$saved = $regForm->save();

if ($saved) {
    return response()->json(['status' => 'success', 'message' => 'Data insertion successful.']);
} else {
    return response()->json(['status' => 'error', 'message' => 'Error inserting data.'], 500);
}
}
function getCountries()
{
    $countries = Country::all();

    // Check if there are any countries
    if ($countries->count() > 0) {
        // Convert the collection of countries to an array
        $countriesArray = $countries->toArray();
        // Return the countries as JSON
        return response()->json($countriesArray);
    } else {
        // No countries found
        return response()->json([]);
    }
}
function getStates(Request $request)
{
    if ($request->has('country_id')) {
        $countryId = $request->input('country_id');

        // Query to select states corresponding to the selected country
        $states = States::where('country_id', $countryId)->get();
    

        // Check if there are any states
        if ($states->isNotEmpty()) {
            // Convert the collection of states to an array
            $statesArray = $states->toArray();
            // Send the states data as JSON response
            return response()->json($statesArray);
        }
        else {
            // Send an empty array if no states found
            return response()->json([]);
        }}
        elseif($request->has('country_name')) {
            $countryName = $request->input('country_name');
    
            // Query to select states corresponding to the selected country name using Eloquent relationships
            $states = States::select('states.*')
        ->join('countries', 'states.country_id', '=', 'countries.id')
        ->where('countries.country_name', $countryName)
        ->get();
    
            // Check if there are any states in the database
            if ($states->isNotEmpty()) {
                // Send the states data as JSON response
                return response()->json($states);
            }  else {
            // Send an empty array if no states found
            return response()->json([]);
        }
    } else {
        // Send an empty array if country_id is not set
        return response()->json([]);
    }
}
function updateData(Request $request)
{
    $request->validate([
        'hidden' => 'required|integer',
        // Add validation rules for other fields if needed
    ]);

    $id = $request->input('hidden');
    $regform = Regform::findOrFail($id);

    // Initialize array to store key-value pairs for the SET clause
    $setValues = [];

    // Loop through request input to construct setValues array
    foreach ($request->except(['hidden', 'fileToUpload']) as $key => $value) {
        // Skip id field
        if ($key !== 'id') {
            $setValues[$key] = $value;
        }
    }

    // Check if a new file is uploaded
    if ($request->hasFile('fileToUpload')) {
        $file = $request->file('fileToUpload');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads'), $filename);

        // Add the image_url to setValues array
        $setValues['image_url'] = $filename;

        // Delete old image if it exists
        if ($regform->image_url) {
            $oldImagePath = public_path('uploads/') . $regform->image_url;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
    }

    // Update the record
    $regform->update($setValues);

    return response()->json([
        'status' => 'success',
        'message' => 'Data updated successfully.'
    ]);
}

function deleteData(Request $request,$id)
{
    
    $regform = Regform::find($id);

            if (!$regform) {
                return response()->json([
                    "status" => "error",
                    "message" => "User not found."
                ]);
            }

            // Delete the image file
            $imagePath = public_path('uploads/' . $regform->image_url);
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the file
            }

            // Delete the user
            $deleted = $regform->delete();

            if ($deleted) {
                return response()->json([
                    "status" => "success",
                    "message" => "User deleted successfully."
                ]);
            } else {
                return response()->json([
                    "status" => "error",
                    "message" => "Error deleting user."
                ]);
            }
        } 
   
}
