<?php

use Dompdf\Dompdf;
    class CreatePDF {

        public $file;
        public $fileTmpName;
        public $fileName;
        public $fileNameWE;
        public $finalFileName;
        public $fileType;
        public $fileError;
        public $fileSize;
        public $arrayData = [];
        public $errors = [];

        public function __construct()
        {
            if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["csvFile"])){

                $this->file =  $_FILES["csvFile"];
                $this->fileTmpName = $this->file["tmp_name"];
                $this->fileName = $this->file["name"];
                $this->fileType = $this->file["type"];
                $this->fileError = $this->file["error"];
                $this->fileSize = $this->file["size"];

                if ($this->fileType != 'text/csv') {
                    $this->errors[] = "Wrong document format. Please select a CSV file.";
                }
            }
        }

        public function setFileNameWE()
        {
            $this->fileNameWE = pathinfo($this->fileName, PATHINFO_FILENAME);
            $this->finalFileName = $this->fileNameWE.'.pdf';
        }

        public function readCsvFile()
        {
            $handle = fopen($this->fileTmpName, 'r');
            $line_number = 0;
            while(($data = fgetcsv($handle, 0, ';', '"', "\\")) !== false) {

                if ($line_number !== 0){
                    $this->arrayData[] = $data;
                }

                $line_number++;
            }

            fclose($handle);
        }

        public function createPDF()
        {
            require('vendor/autoload.php');
            $dompdf = new Dompdf();
            $html =
                '<html>
                    <head>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                background-color: antiquewhite;
                                padding: 1rem;
                            }
                            h1 {
                                color: #007bff;
                            }
                            .signature {
                                float: right;
                                padding: 1rem;
                            }                            
                        </style>
                    </head>
                    <body>';
            $html .= '<div><h1>PrisListe</h1> <span>'.date("d-m-Y").'</span></div>';
            $html .= '<table border="1" width="100%">';
            $html .= '<tr><th>Nr</th><th>Produktnavn</th><th>Pris</th></tr>';
            foreach ($this->arrayData as $data) {
                $html .= '<tr>';
                $html .= '<td>' . $data[0] . '</td>';
                $html .= '<td>' . $data[1] . '</td>';
                $html .= '<td>' . $data[2] . '</td>';
                $html .= '</tr>';

            }
            $html .= '</table>';
            $html .= '<div class="signature">Signature</div>';
            $html .= '</body></html>';

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $pdfOutput = $dompdf->output();
            file_put_contents('generated_pdf/'.$this->fileNameWE.'.pdf', $pdfOutput);
        }

    }


    $instance = new CreatePDF();
    $instance->readCsvFile();
    $instance->setFileNameWE();
    $result = $instance->createPDF();

    print_r(json_encode(['fileName' => $instance->finalFileName]));