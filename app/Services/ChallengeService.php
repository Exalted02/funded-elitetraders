<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ChallengeService
{
	public function generateCertificate($challenge_id, $name, $amount, $date)
	{
		$amount = number_format($amount, 2, '.', ',').get_currency_symbol();
		$date = change_date_format($date, 'Y-m-d', 'd M y');

		// Load image
		$manager = new ImageManager(new Driver());
		$image = $manager->read(public_path('certificate/certificate.png')); // your certificate background
		$imageWidth = $image->width();
		// Font path
		$fontPath = public_path('fonts/arialbi.ttf');

		// Add Name
		$image->text($name, $imageWidth / env('CERTIFICATE_NAME_HORIZONTAL'), env('CERTIFICATE_NAME_VERTICAL'), function ($font) {
			$font->filename(public_path('fonts/arialbi.ttf'));
			$font->size(80);
			$font->color('#ffffff');
			$font->align('center');
			$font->valign('middle');
			$font->angle(0);
		});

		// Add Profit
		/*$image->text($amount, $imageWidth / 2, 680, function ($font) {
			$font->filename($fontPath);
			$font->size(100);
			$font->color('#ffffff');
			$font->align('center');
			$font->valign('middle');
			$font->angle(0);
		});*/

		// Add Date
		$image->text($date, $imageWidth - env('CERTIFICATE_DATE_HORIZONTAL'), env('CERTIFICATE_DATE_VERTICAL'), function ($font) {
			$font->filename(public_path('fonts/arialbd.ttf'));
			$font->size(25);
			$font->color('#ffffff');
			$font->align('right');
			$font->valign('bottom');
			$font->angle(0);
		});

		// Save or return response
		$imagePath = public_path('certificate/'.$challenge_id.'.png');
		$image->save($imagePath);
		return $imagePath;
		return response()->download($imagePath);
	}
	public function generateVerificationCertificate($certificate_id, $name, $amount, $date)
	{
		$amount = get_currency_symbol().number_format($amount, 2, '.', ',');
		$date = change_date_format($date, 'Y-m-d', 'd M y');

		// Step 1: Generate QR code (PNG format)
        $verificationUrl = route('certificate.view', $certificate_id);
        $qrPath = public_path("qrcodes/{$certificate_id}.png");
		if (!file_exists(dirname($qrPath))) {
            mkdir(dirname($qrPath), 0777, true);
        }

        file_put_contents(
            $qrPath,
            QrCode::format('png')->size(150)->generate($verificationUrl)
        );
		
		
		// Load image
		$manager = new ImageManager(new Driver());
		$image = $manager->read(public_path('verification-certificate/certificate.png')); // your certificate background
		$imageWidth = $image->width();
		// Font path
		$fontPath = public_path('fonts/arialbi.ttf');

		// Add Image
		$qrImage = $manager->read($qrPath);
        $x = intval(($imageWidth - $qrImage->width()) / 2); // center horizontally
		$y = intval($image->height() - $qrImage->height() - 50); // 50px from bottom

		$image->place($qrImage, 'top-left', $x, $y);
		
		// Add Name
		$image->text($name, $imageWidth / 2, 510, function ($font) {
			$font->filename(public_path('fonts/arialbd.ttf'));
			$font->size(80);
			$font->color('#ffffff');
			$font->align('center');
			$font->valign('middle');
			$font->angle(0);
		});

		// Add Profit
		$image->text($amount, $imageWidth / 2, 680, function ($font) {
			$font->filename(public_path('fonts/arialbd.ttf'));
			$font->size(100);
			$font->color('#ffffff');
			$font->align('center');
			$font->valign('middle');
			$font->angle(0);
		});

		// Add Date
		$image->text($date, $imageWidth - 150, 930, function ($font) {
			$font->filename(public_path('fonts/arialbd.ttf'));
			$font->size(25);
			$font->color('#ffffff');
			$font->align('right');
			$font->valign('bottom');
			$font->angle(0);
		});

		// Save or return response
		$imagePath = public_path('verification-certificate/'.$certificate_id.'.png');
		$image->save($imagePath);
		return $imagePath;
		return response()->download($imagePath);
	}
}
