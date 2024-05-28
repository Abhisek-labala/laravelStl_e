<!DOCTYPE html>
<html>

<head>
    <title>Certificate</title>
    <style>
       

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

        header {
            display: flex;
            justify-content: space-between;
        }

        footer {
            display: flex;
            justify-content: space-between;
        }

        .row {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-left: 10px;
        }

        .row h3 {
            margin-bottom: 10px;
        }


        #logo {
            height: 8rem;
            width: auto;
            margin: 0;
            padding: 0;
        }

        .main {
            margin: 0;
            padding: 0;
        }

        .maincontenet {
            font-family: Helvetica, sans-serif;
            font-style: italic;
            color: brown;
        }

        .modal-body {
    position: relative; /* Ensure relative positioning for absolute content */
}

.modal-body::before {
    content: '';
    background-image: url('{{ asset("uploads/Bu.png") }}');
    background-position: center;
    background-repeat: no-repeat;
    opacity: 0.3; 
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    position: absolute;
}

    </style>
</head>

<body>
    <div class="container">
        <header>
            <div class="row1">
            <h3>Serial No. {{ $rollno }}</h3>

                <img src=" {{ asset($barcodePath) }}" style="height: 40px;" alt="Barcode">

            </div>
            <div class="row">
                <h3>Roll No. 64895646</h3>
                <img src="{{ asset( $qrCodePath ) }}" style="height: 70px; width: 70px;" alt="QR Code">
            </div>
        </header>

        <div class="main">
            <img src="{{ asset('uploads/bu2.png') }}" style="height: 120px;" alt="image">

            <h1>BERHAMPUR UNIVERSITY</h1>
            <p>(ACCREDITED WITH "A" GRADE)</p>
        </div>
        <div class="maincontenet">
            <p>This is certify that</p>
            <p>{{ $name }}</p>
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
            <div class="footerrow" style="padding: 0; margin: 0;">
                <p>Bhanja Bihar</p>
                <p>Berhampur-760007(odisha)</p>
                <p>Date: June 16,2022</p>
            </div>
            <div class="footerrow2">
                <img src="{{ asset('uploads/signature.png') }}" style="height: 70px;"alt="signature">
                <p>Vice-Chancellor</p>
            </div>

        </footer>
    </div>
</body>

</html>
