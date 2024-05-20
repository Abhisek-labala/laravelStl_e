<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mpdf\Mpdf;
use Endroid\QrCode\Builder\Builder;
use Picqer\Barcode\BarcodeGeneratorPNG;

class CertificateController extends Controller
{
    public function generate(Request $request)
    {

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
        ]);
        $font = $mpdf->SetFont('dejavusansmono');



        $html = '
    <style>
    body {
        font-family: "' . $font . '", sans-serif;
    }
    
    .container {
        border: 10px solid black;
        width: 700px;
        margin: auto;
        text-align: center;
        z-index: 1000;
        height: 100%;
    }
    .head {
        font-size: 24px;
        font-weight: bold;
        margin-top: 50px;
        color: brown;
    }
    

    .row1 {
        width: 40%; 
        float: left;
        text-align: left;
        margin-left:10px;
        margin-top:5px;
    }

    .row {
        width: 40%; 
        float: right;
        text-align: right;

        margin-right:10px;
        margin-top:5px;
    }
    .footerrow, {
        width: 70%; 
        float: left;
        text-align: left;
        margin-left:10px;
        margin-bottom:5px;
    }
    .footerrow1 {
        width: 30%; 
        float: right;
        text-align: right;

        margin-right:10px;
        margin-bottom:5px;
    
    }
    .footer img {
        height: 80px;
        vertical-align: middle; 
    }
  
    .row h3 {
        margin-bottom: 10px;
    }
    .qr-code {
        margin-top: 5px;
    }
    img {
        margin-right: 10px;
        height: 50px;
    }
 
    .maincontenet {
        padding-bottom :80px;
        font-style: italic;
        color: brown;
    }

    
    </style>';
        $name = $request->requestData['Name'];
        $rollno = $request->requestData['Rollno'];
        $qrCode = Builder::create()
            ->data($name)
            ->size(200)
            ->margin(10)
            ->build();
        $generator = new BarcodeGeneratorPNG();
        $barcodePath = public_path('uploads/barcode_' . $rollno . '.png');
        file_put_contents($barcodePath, $generator->getBarcode($rollno, $generator::TYPE_CODE_128));
        $image = public_path('uploads/bu2.png');
        $qrCodePath = public_path('uploads/qrcode.png');
        $qrCode->saveToFile($qrCodePath);
        $sign = public_path('uploads/signature.png');


        $html .= '<body>
        <div class="container">
            <header>
                <div class="row1">
                    <h3>Serial No. ' . $rollno . '</h3>
                    <img src="' . $barcodePath . '" alt="Barcode">
    
                </div>
                <div class="row">
                    <h3>Roll No. 64895646</h3>
                    <img src="' . $qrCodePath . '" alt="QR Code">
                </div>
            </header>
    
            <div class="main">
                <img src="' . $image . '" style="height: 120px;" alt="image">
    
                <h1>BERHAMPUR UNIVERSITY</h1>
                <p>(ACCREDITED WITH "A" GRADE)</p>
            </div>
            <div class="maincontenet">
                <p>This is certify that</p>
                <p>' . $name . '</p>
                <p>of Model degree college,Rayagada</p>
                <p>has Succesfully completed the requirements prescribed</p>
                <p>under the Regulation for </p>
                <p>the award of the degree of</p>
                <h3>Bachelor Of Science</h3>
                <h3>Computer Science Honors</h3>
                <p>held in the month of january 2021</p>
                <p>was on this day admitted to the degree with </p>
                <h4>CGPA 7.10, Grade-A</h4>
            </div>
            <footer>
                <div class="footerrow"><br>
                    <p>Bhanja Bihar</p>
                    <p>Berhampur-760007(odisha)</p>
                    <p>Date: June 16,2022</p>
                </div>
                <div class="footerrow2" style="margin-right:2px;"><br>
                    <img src="' . $sign . '" alt="signature" style="height: 80px;">
                    <p>Vice-Chancellor</p>
                </div>
    
            </footer>
        </div>
    </body>';

        // Write HTML content to mPDF
        $mpdf->WriteHTML($html);
        $watermarkImagePath = public_path('uploads/Bu.png');
        $mpdf->SetWatermarkImage($watermarkImagePath);
        $mpdf->showWatermarkImage = true;
        $mpdf->watermarkImageAlpha = 0.3;

        // Output the PDF content
        $pdfContent = $mpdf->Output('', 'S');

        // Return PDF content
        return response()->json(['pdf_content' => base64_encode($pdfContent)]);
    }
}