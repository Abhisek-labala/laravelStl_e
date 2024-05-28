<?php

namespace App\Http\Controllers;

require_once 'F:\Xampp\htdocs\stle_project\PHPExcel-v7.4\PHPExcel.php';
use App\Models\RegForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Fill;
use PHPExcel_Worksheet;
use App\Models\Country;
use App\Models\States;
use DateTime;
use Illuminate\Support\Facades\Validator;


class ExcelController extends Controller
{
    public function generateExcel()
    {
        // Create a new Spreadsheet object
        $spreadsheet = new \PHPExcel();
        $spreadsheet->getProperties()
            ->setCreator('Abhisek')
            ->setTitle('userinfo')
            ->setLastModifiedBy('Abhisek')
            ->setDescription('user data updation')
            ->setSubject('user information')
            ->setKeywords('phpexcel implementation')
            ->setCategory('importing');

        $ews = $spreadsheet->getActiveSheet();
        $ews->setTitle('Userdata');

        $ews->setCellValue('a1', 'name');
        $ews->setCellValue('b1', 'email');
        $ews->setCellValue('c1', 'phone');
        $ews->setCellValue('d1', 'dob');
        $ews->setCellValue('e1', 'address');
        $ews->setCellValue('f1', 'country');
        $ews->setCellValue('g1', 'state');
        $ews->setCellValue('h1', 'username');
        $ews->setCellValue('i1', 'gender');
        $ews->setCellValue('j1', 'hobbies');

        $header = 'a1:j1';
        $ews->getStyle($header)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00ffff00');
        $style = array(
            'font' => array('bold' => true, ),
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER, ),
        );
        $ews->getStyle($header)->applyFromArray($style);

        // Set column widths
        for ($col = ord('a'); $col <= ord('j'); $col++) {
            $ews->getColumnDimension(chr($col))->setAutoSize(true);
        }

        $ews2 = new \PHPExcel_Worksheet($spreadsheet, 'Reference');
        $spreadsheet->addSheet($ews2, 0);
        $ews2->setTitle('Reference');

        // Add headers for additional columns
        $ews2->setCellValue('a1', 'Country ID');
        $ews2->setCellValue('b1', 'Country Name');
        $ews2->setCellValue('c1', 'State ID');
        $ews2->setCellValue('d1', 'State Name');
        $ews2->setCellValue('e1', 'c_id');
        $ews2->setCellValue('f1', 'Hobbies');
        $ews2->setCellValue('g1', 'Gender');

        $headerReference = 'a1:g1';
        $ews2->getStyle($headerReference)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00ff0000');
        $ews2->getStyle($headerReference)->applyFromArray($style);

        // Set column widths for Reference sheet
        for ($col = ord('a'); $col <= ord('g'); $col++) {
            $ews2->getColumnDimension(chr($col))->setAutoSize(true);
        }

        // Fetch hobbies and gender data
        $hobbiesArray = array(
            1 => 'Cricket',
            2 => 'Football',
            3 => 'Dancing',
            4 => 'Travelling',
            5 => 'Indoor games'
        );

        $genderArray = array(
            1 => 'Male',
            2 => 'Female',
        );

        // Populate hobbies and gender data in the Reference sheet
        $row = 2; // Start from row 2 (after headers)
        foreach ($hobbiesArray as $id => $hobby) {
            $ews2->setCellValue('f' . $row, $hobby);
            $row++;
        }

        $row = 2; // Reset row for gender
        foreach ($genderArray as $id => $gender) {
            $ews2->setCellValue('g' . $row, $gender);
            $row++;
        }

        // Fetch country and state data
        $countries = Country::all();
        $states = States::all();

        // Populate country data in the Reference sheet
        $row = 2; // Start from row 2 (after headers)
        foreach ($countries as $country) {
            $ews2->setCellValue('a' . $row, $country->id);
            $ews2->setCellValue('b' . $row, $country->country_name);
            $row++;
        }

        // Populate state data in the Reference sheet
        $row = 2; // Reset row for states
        foreach ($states as $state) {
            $ews2->setCellValue('c' . $row, $state->sid);
            $ews2->setCellValue('d' . $row, $state->state_name);
            $ews2->setCellValue('e' . $row, $state->country_id);
            $row++;
        }

        // Fetch user data
        $userData = DB::table('users')->get();

        // Populate user data
        $row = 2; // Start from row 2 (after headers)
        foreach ($userData as $user) {
            $ews->setCellValue('a' . $row, $user->name);
            $ews->setCellValue('b' . $row, $user->email);
            $ews->setCellValue('c' . $row, $user->phone);
            $ews->setCellValue('d' . $row, $user->dob);
            $ews->setCellValue('e' . $row, $user->address);
            $ews->setCellValue('f' . $row, $user->country);
            $ews->setCellValue('g' . $row, $user->state);
            $ews->setCellValue('h' . $row, $user->username);
            $ews->setCellValue('i' . $row, $genderArray[$user->gender]);
            
            // Parse hobbies IDs and display them as strings
            $hobbies = explode(',', $user->hobbies);
            $hobbiesString = '';
            foreach ($hobbies as $hobbyId) {
                if (isset($hobbiesArray[$hobbyId])) {
                    $hobbiesString .= $hobbiesArray[$hobbyId] . ', ';
                }
            }
            $hobbiesString = rtrim($hobbiesString, ', '); // Remove trailing comma and space
            $ews->setCellValue('j' . $row, $hobbiesString);

            $row++;
        }

        // Set headers to prompt file download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PHPExcel_IOFactory::createWriter($spreadsheet, 'Excel2007');
        ob_end_clean();
        $writer->setIncludeCharts(true);
        $writer->save('php://output');
        exit;
    }
    function excelValidation(Request $request)
    {
           $insertCount = 0;
            $updateCount = 0;
        
            $response = [];
        
            if ($request->file('spreedsheetfile')->isValid()) {
                // Access uploaded file details
                $uploadedFile = $request->file('spreedsheetfile');
                $uploadedFileName = $uploadedFile->getClientOriginalName();
                $uploadedFileType = $uploadedFile->getClientMimeType();
                $uploadedFileTmp = $uploadedFile->getPathName();
        
                // Check file type
                if (in_array($uploadedFileType, ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv'])) {
                    // Load PHPExcel library for XLS and XLSX files
                    require '../PHPExcel-v7.4/PHPExcel/IOFactory.php';
        
                    // Load the uploaded file
                    if ($uploadedFileType === 'text/csv') {
                        // For CSV files, read using PHP built-in functions
                        $fileData = file_get_contents($uploadedFileTmp);
                        $csvData = str_getcsv($fileData, "\n"); // Assuming each row is separated by newline
                        $uploadedHeaders = str_getcsv($csvData[0]); // Assuming headers are in the first row
        
                        // Perform data validation for CSV files
                        $errors = [];
                        $emptyCells = []; // Collect empty cell indices
                        foreach ($csvData as $key => $row) {
                            $rowData = str_getcsv($row);
                            foreach ($rowData as $index => $value) {
                                if (empty($value)) {
                                    $errors[] = "Error: Field '{$uploadedHeaders[$index]}' in row " . ($key + 1) . " is empty.";
                                    $emptyCells[] = "Row: " . ($key + 1) . ", Column: " . ($index + 1); // Collect empty cell indices
                                }
                            }
                           
                            $email = $rowData[1]; // Assuming email is in the second column
                            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $errors[] = "Error: Invalid email format in row " . ($key + 1) . ".";
                            }
                        }
        
                        if (!empty($errors)) {
                            $response['errors'] = $errors;
                            return response()->json($response);
                        }
                    } else {
                        // For XLS and XLSX files, use PHPExcel library
                        $objPHPExcel = \PHPExcel_IOFactory::load($uploadedFileTmp);
                        $uploadedHeaders = $objPHPExcel->getActiveSheet()->toArray()[0];
        
                        // Perform data validation for XLS and XLSX files
                        $errors = [];
                        $emptyCells = []; // Collect empty cell indices
                        foreach ($objPHPExcel->getActiveSheet()->toArray() as $key => $row) {
                            foreach ($row as $index => $value) {
                                if ($key === 0 && empty($value)) {
                                    $errors[] = "Error: Field '{$uploadedHeaders[$index]}' in row " . ($key + 1) . " is empty.";
                                }
                                if (empty($value)) {
                                    $emptyCells[] = "Row: " . ($key + 1) . ", Column: " . ($index + 1); // Collect empty cell indices
                                }
                            }
                            // Check if any cell in the row is empty
                            if (count(array_filter($row)) !== count($row)) {
                                $emptyCells[] = "Row: " . ($key + 1) . ", Column: " . ($index + 1);
                            }                            
                        }
                        //email validation
                        $rows = array_slice($objPHPExcel->getActiveSheet()->toArray(), 1); // Exclude the header row
        
                        foreach ($rows as $key => $row) {
                            $email = $row[1]; 
                            $date = $row[3];
                        
                            if (empty($email)) {
                                $emptyCells[] = "Row: " . ($key + 2) . ", Column: 2"; // Adjust row number
                            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $errors[] = "Error: Invalid email format in row " . ($key + 2) . ".";
                            }
                            if (!empty($date)) {
                                $dateObj = \DateTime::createFromFormat('Y-m-d', $date);
                                if (!$dateObj || $dateObj->format('Y-m-d') !== $date) {
                                    $errors[] = "Error: Invalid date format in row " . ($key + 2) . ". It should be in yyyy-mm-dd format.";
                                }
                            } else {
                                $emptyCells[] = "Row: " . ($key + 2) . ", Column: 3"; // Adjust row number
                            }
                        }
                        
                        if (!empty($errors)) {
                            $response['errors'] = $errors;
                            return response()->json($response);
                        }
                    }
        
                    // header template comparing
                    $templateHeaders = ['name', 'email', 'phone', 'dob', 'address', 'country', 'state', 'username', 'gender', 'hobbies'];
        
                    if ($uploadedHeaders !== $templateHeaders) {
                        $response['error'] = "Uploaded file doesn't match the template. Please upload the correct file.";
                        return response()->json($response);
                    }
        
                    $response['message'] = "File matches the template.";
                    $referenceSheet = $objPHPExcel->getSheetByName('Reference');
        
                    // Get reference data for countries
                    $highestRow = $referenceSheet->getHighestRow(); // Get the highest row number
                    $countries = [];
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $country = $referenceSheet->getCell('A' . $row)->getValue();
                        $countries[] = $country;
                    }
        
                    // Get reference data for states
                    $states = [];
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $state = $referenceSheet->getCell('C' . $row)->getValue();
                        $states[] = $state;
                    }
        
                    // Get reference data for hobbies
                    $hobbies = [];
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $hobby = $referenceSheet->getCell('F' . $row)->getValue();
                        $hobbies[] = $hobby;
                    }
        
                    // Get reference data for genders
                    $genders = [];
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $gender = $referenceSheet->getCell('G' . $row)->getValue();
                        $genders[] = $gender;
                    }
        
                    $errors = [];
        
                    foreach ($uploadedHeaders as $index => $header) {
                        if ($header === 'country') {
                            $columnIndex = $index;
                            foreach ($objPHPExcel->getActiveSheet()->toArray() as $key => $row) {
                                if ($key !== 0 && !in_array($row[$columnIndex], $countries)) {
                                    $errors[] = "Error: Country '{$row[$columnIndex]}' in row " . ($key + 1) . " is not present in the reference data.";
                                }
                            }
                        } elseif ($header === 'state') {
                            $columnIndex = $index;
                            foreach ($objPHPExcel->getActiveSheet()->toArray() as $key => $row) {
                                if ($key !== 0 && !in_array($row[$columnIndex], $states)) {
                                    $errors[] = "Error: State '{$row[$columnIndex]}' in row " . ($key + 1) . " is not present in the reference data.";
                                }
                            }
                        } elseif ($header === 'gender') {
                            $columnIndex = $index;
                            foreach ($objPHPExcel->getActiveSheet()->toArray() as $key => $row) {
                                if ($key !== 0 && !in_array($row[$columnIndex], $genders)) {
                                    $errors[] = "Error: Gender '{$row[$columnIndex]}' in row " . ($key + 1) . " is not present in the reference data.";
                                }
                            }
                        } elseif ($header === 'hobbies') {
                            $columnIndex = $index;
                            foreach ($objPHPExcel->getActiveSheet()->toArray() as $key => $row) {
                                if ($key !== 0) {
                                    $hobbiesList = explode(',', $row[$columnIndex]);
                                    foreach ($hobbiesList as $hobby) {
                                        // Trim each hobby to remove any leading or trailing whitespace
                                        $hobby = trim($hobby);
                                        if (!in_array($hobby, $hobbies)) {
                                            $errors[] = "Error: Hobby '{$hobby}' in row " . ($key + 1) . " is not present in the reference data.";
                                        }
                                    }
                                }
                            }
                        } elseif ($header === 'username') {
                            $columnIndex = $index;
                            $usernames = [];
                            foreach ($objPHPExcel->getActiveSheet()->toArray() as $key => $row) {
                                if ($key !== 0) {
                                    $username = $row[$columnIndex];
                                    if (in_array($username, $usernames)) {
                                        $errors[] = "Error: Duplicate entry found for username '{$username}' in row " . ($key + 1) . ".";
                                    } else {
                                        $usernames[] = $username;
                                    }
                                }
                            }
                        } elseif ($header === 'phone') {
                            $columnIndex = $index;
                            foreach ($objPHPExcel->getActiveSheet()->toArray() as $key => $row) {
                                if ($key !== 0) {
                                    $phone = $row[$columnIndex];
                                    if (!is_numeric($phone)) {
                                        $errors[] = "Error: Phone number '{$phone}' in row " . ($key + 1) . " is not numeric.";
                                    }
                                }
                            }
                        }
                    }
        
                    if (!empty($errors)) {
                        $response['errors'] = $errors;
                        return response()->json($response);
                    }
        
                    // Display data from the Excel file
                    $tableHTML = '<table>';
                    $tableHTML .= '<thead><tr>';
                    foreach ($uploadedHeaders as $header) {
                        $tableHTML .= '<th>' . $header . '</th>';
                    }
                    $tableHTML .= '</tr></thead><tbody>';
                    foreach ($objPHPExcel->getActiveSheet()->toArray() as $key => $row) {
                        if ($key !== 0) { // Skip the header row
                            $tableHTML .= '<tr>';
                            foreach ($row as $value) {
                                $tableHTML .= '<td>' . $value . '</td>';
                            }
                            $tableHTML .= '</tr>';
                        }
                    }
                    $tableHTML .= '</tbody></table><br>';
                    $tableHTML .= '<button type="button" class="btn btn-success" id="uploadButton">Upload</button>';
        
                    $response['tableHTML'] = $tableHTML;
        
                    // Display empty cell indices
                    if (!empty($emptyCells)) {
                        $response['emptyCells'] = $emptyCells;
                    }
                } else {
                    $response['error'] = "Please upload a valid file (CSV, XLS, or XLSX).";
                }
            } else {
                $response['error'] = "Error uploading file.";
            }
        
            return response()->json($response);        
    }
    public function excelUpload(Request $request)
{
    

    $response = []; // Initialize response array

    // Check if the file was uploaded successfully
    if ($request->hasFile('spreedsheetfile') && $request->file('spreedsheetfile')->isValid()) {
        // Access uploaded file details
        $uploadedFile = $request->file('spreedsheetfile');
        $uploadedFileName = $uploadedFile->getClientOriginalName();
        $uploadedFileTmp = $uploadedFile->getPathName();

        $insertCount = 0;
        $objPHPExcel = PHPExcel_IOFactory::load($uploadedFileTmp); // Load Excel file

        // Example code to insert data into the database
        foreach ($objPHPExcel->getActiveSheet()->toArray() as $key => $row) {
            if ($key !== 0) { // Skip header row
                // Check if the index exists before accessing it
                if (isset($row[9])) {
                    $username = $row[7];
                    $name = $row[0];
                    $email = $row[1];
                    $phone = $row[2];
                    $dob = $row[3];
                    $address = $row[4];
                    $country = $row[5];
                    $state = $row[6];
                    $gender = $row[8];
                    $hobbies = $row[9];
                    $pass = "password";
                    $phash = bcrypt($pass);

                    // Check if user already exists
                    $existingUser = RegForm::where('username', $username)->first();
                    if (!$existingUser) {
                        // Insert data into the database
                        $regForm = new RegForm();
                        $regForm->name = $name;
                        $regForm->email = $email;
                        $regForm->phone = $phone;
                        $regForm->dob = $dob;
                        $regForm->password = $phash;
                        $regForm->address = $address;
                        $regForm->country = $country;
                        $regForm->state = $state;
                        $regForm->username = $username;
                        $regForm->gender = $gender;
                        $regForm->hobbies = $hobbies;
                        $regForm->save();
                        $insertCount++;
                    } else {
                        $response['error'] = "Data Already present in the database";
                    }
                } else {
                    // Handle missing data or move to the next row
                    continue;
                }
            }
        }

        // Prepare the response
        $response['insertCount'] = $insertCount;
        $response['message'] = "Data insertion completed.";

        return response()->json($response); // Return response as JSON
    } else {
        // Handle file upload error
        $response['error'] = "File upload error.";
        return response()->json($response); // Return error response as JSON
    }}
}
