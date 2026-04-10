<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BumpContentVersionRequest;
use App\Models\ContentVersion;
use Illuminate\Http\RedirectResponse;

class ContentVersionController extends Controller
{
    public function __invoke(BumpContentVersionRequest $request): RedirectResponse
    {
        $contentVersion = ContentVersion::current();
        $module = $request->string('module')->value();

        $contentVersion->bump($module);

        return to_route('admin.content-versions.index')->with('status', __(':module versiyonu artırıldı.', [
            'module' => strtoupper($module),
        ]));
    }
}
