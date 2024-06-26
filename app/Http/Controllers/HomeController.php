<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class HomeController extends Controller
{
    function post(Request $request)
    {
        $data = $request->all(); 
        // Stamp scale is 1.5, change to 1.
        $stampX = ($data['stampX'] / 1.5);
        $stampY = ($data['stampY'] / 1.5);
        $canvasHeight = $data['canvasHeight'] / 1.5;
        $canvasWidth = $data['canvasWidth'] / 1.5;
        $page = $data['pageID'];
        $urlPdf = $data['urlPdf'];
        $operator = $data['Signature'];

        // $qrRandCode = rand(1000, 9999);
        // $qrImageString = QrCode::format('png')->generate('https://document.patratrading.com/validate?qris=' . $qrRandCode);
            $qrPath =  public_path('Basmalah.png');
        // $qrPath = 'PTr-'.$qrRandCode.'.png';
        // Storage::disk('public')->put($qrPath, $qrImageString);
        // $qrPath = Storage::disk('public')->path($qrPath);
 
        // File Reading
        $pdfContent = file_get_contents($urlPdf); 
        $localFilePath = storage_path('app/public/lkp.pdf');
        file_put_contents($localFilePath, $pdfContent); 
        $pageCount = PDF::setSourceFile($localFilePath);
            
            $template = PDF::importPage($page);
            $size = PDF::getTemplateSize($template);

            PDF::AddPage($size['orientation'], array($size['width'], $size['height']));
            PDF::useTemplate($template);

            $widthDiffPercent = ($canvasWidth - $size['width']) / $canvasWidth * 100;
            $heightDiffPercent = ($canvasHeight - $size['height']) / $canvasHeight * 100;

            $realXPosition = $stampX - ($widthDiffPercent * $stampX / 100);
            $realYPosition = $stampY - ($heightDiffPercent * $stampY / 100);
 
            PDF::SetAutoPageBreak(false);
            PDF::Image($qrPath, $realXPosition, $realYPosition, 24.56, 19.87, 'PNG');
            

        $pdfContent = PDF::Output('TheArKa.pdf', 'S'); // Output PDF ke dalam string
        $filePath = public_path('TheArKa.pdf');
        file_put_contents($filePath, $pdfContent); // save to external server
        $newUrl = asset('TheArKa.pdf');   

        // I: Show to Browser, D: Download, F: Save to File, S: Return as String
        return view('welcome', ['pdfUrl' => $newUrl]);
    }
}
