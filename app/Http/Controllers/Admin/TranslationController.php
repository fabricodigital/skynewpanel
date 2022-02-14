<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TranslationExport;
use App\Http\Controllers\Controller;
use App\Imports\TranslationImport;
use App\Services\TranslatorService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TranslationController extends Controller
{
    /**
     * @var TranslatorService
     */
    protected $translator;

    /**
     * TranslationController constructor.
     */
    public function __construct()
    {
        $this->translator = new TranslatorService();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $langNames = $this->translator->getLangNames();
        $langKeys = $this->translator->getLangKeys();

        $strings = $this->translator->getStrings();

        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
        header("Pragma: no-cache"); // HTTP 1.0.
        header("Expires: 0");

        return view('admin.translations.index', compact('strings', 'langNames', 'langKeys'));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function postUpdateStrings()
    {
        if(!Auth::user()->hasRole('Super Administrator')) {
            abort(401);
        }
        $dataStrings = request('strings');

        $this->translator->saveFiles($dataStrings);

        return response()->json([
            'success' => true,
            'message' => __('Translated strings updated successfully!')
        ], 201);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postScanForStrings()
    {
        if(!Auth::user()->hasRole('Super Administrator')) {
            abort(401);
        }

        $this->translator->scanStrings();

        return redirect()->back()
            ->with('success', __('All strings successfully added'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postExportStrings()
    {
        if(!Auth::user()->hasRole('Super Administrator')) {
            abort(401);
        }

        return Excel::download(new TranslationExport, __('Strings :date_time.xlsx', ['date_time' => Carbon::now()->format('Y-m-d H:i:s')]));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function postImportStrings()
    {
        if(!Auth::user()->hasRole('Super Administrator')) {
            abort(401);
        }

        $this->validate(request(), [
            'strings_file' => 'required|file'
        ]);

        Excel::import(new TranslationImport(), request()->file('strings_file'));

        return redirect()->back()
            ->with('success', __('All strings successfully added'));
    }
}
