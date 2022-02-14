<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\FaqCategory;
use App\Models\Admin\FaqQuestion;
use App\Models\Admin\Revision;

class FaqShowController extends Controller
{
    public function __invoke()
    {
        $account = auth()->user()->account;

        $faqCategories = FaqCategory::with('questions')->where('account_id', $account->id)->get();
        $logo = $account->getMedia('logo');

        if (!$logo->isEmpty()) {
            $logo = $logo[0]->getFullUrl();
        } else {
            $logo = '';
        }

        return view('admin.faq', compact('faqCategories', 'logo'));
    }
}
