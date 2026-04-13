<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MobileFeedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MobileFeedbackController extends Controller
{
    public function index(): View
    {
        return view('admin.mobile-feedbacks.index', [
            'feedbacks' => MobileFeedback::query()
                ->latest('id')
                ->paginate(20),
        ]);
    }

    public function show(MobileFeedback $mobileFeedback): View
    {
        return view('admin.mobile-feedbacks.show', [
            'feedback' => $mobileFeedback,
        ]);
    }

    public function update(Request $request, MobileFeedback $mobileFeedback): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:'.MobileFeedback::STATUS_NEW.','.MobileFeedback::STATUS_REVIEWED],
        ]);

        $mobileFeedback->update([
            'status' => $validated['status'],
        ]);

        return to_route('admin.mobile-feedbacks.show', $mobileFeedback)
            ->with('status', 'Geri bildirim durumu guncellendi.');
    }
}
