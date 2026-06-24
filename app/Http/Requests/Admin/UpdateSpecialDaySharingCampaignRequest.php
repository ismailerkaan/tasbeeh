<?php

namespace App\Http\Requests\Admin;

class UpdateSpecialDaySharingCampaignRequest extends StoreSpecialDaySharingCampaignRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'images' => ['nullable', 'array', 'max:10'],
            'remove_image_ids' => ['nullable', 'array'],
            'remove_image_ids.*' => ['integer', 'min:1'],
        ]);
    }
}
