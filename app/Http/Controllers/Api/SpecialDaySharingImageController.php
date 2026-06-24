<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SpecialDaySharingImage;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SpecialDaySharingImageController extends Controller
{
    public function __invoke(SpecialDaySharingImage $image): StreamedResponse|Response
    {
        $disk = Storage::disk('public');
        abort_unless($disk->exists($image->path), 404);

        return $disk->response($image->path, $image->original_name, [
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
