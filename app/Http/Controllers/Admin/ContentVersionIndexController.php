<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentVersion;
use Illuminate\View\View;

class ContentVersionIndexController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.content-versions.index', [
            'contentVersion' => ContentVersion::current(),
        ]);
    }
}
