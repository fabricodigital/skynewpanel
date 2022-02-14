<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNoteRequest;
use App\Http\Requests\Admin\UpdateNoteRequest;
use App\Models\Admin\Note;
use App\Models\Admin\Revision;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class NoteController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view_index', Note::class);

        $dataTableObject = Note::getDataTableObject('noteDataTable', route('admin.datatables.notes'));

        return view('admin.notes.index', compact('dataTableObject'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Note::class);

        $note = Note::class;

        return view('admin.notes.create', compact('note'));
    }

    /**
     * @param StoreNoteRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreNoteRequest $request)
    {
        $this->authorize('create', Note::class);

        $note = Note::create($request->validated());

        return redirect()->to(handle_redirect_route_after_crud_action('admin.notes', $note))
            ->with('success', Note::getMsgTrans('created'));
    }

    /**
     * @param $noteId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($noteId)
    {
        $note = Note::withTrashed()->findOrFail($noteId);
        $this->authorize('view', $note);

        $revisionsDataTableObject = Revision::getDataTableObject('noteRevisionsDataTable', route('admin.datatables.revisions', ['model_type' => get_class($note), 'model_id' => $note->id]));

        return view('admin.notes.show', [
            'note' => $note,
            'revisionsDataTableObject' => $revisionsDataTableObject,
        ]);
    }

    /**
     * @param Note $note
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Note $note)
    {
        $this->authorize('update', $note);

        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
        header("Pragma: no-cache"); // HTTP 1.0.
        header("Expires: 0");

        return view('admin.notes.edit', compact('note'));
    }

    /**
     * @param UpdateNoteRequest $request
     * @param Note $note
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateNoteRequest $request, Note $note)
    {
        $this->authorize('update', $note);

        $note->update($request->validated());

        return redirect()->to(handle_redirect_route_after_crud_action('admin.notes', $note))
            ->with('success', Note::getMsgTrans('updated'));
    }

    /**
     * @param $noteId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($noteId)
    {
        $note = Note::withTrashed()->findOrFail($noteId);

        if ((int) request('delete_forever') === 1) {
            $this->authorize('delete_forever', $note);
            $note->forceDelete();
        } else {
            $this->authorize('delete', $note);
            $note->delete();
        }

        return redirect()->route('admin.notes.index')
            ->with('success', Note::getMsgTrans('deleted'));
    }

    /**
     * @param $noteId
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function restore($noteId)
    {
        $note = Note::withTrashed()->findOrFail($noteId);

        $this->authorize('restore', $note);

        $note->restore();

        return redirect()->route('admin.notes.index')
            ->with('success', Note::getMsgTrans('restored'));
    }

    /**
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function datatable()
    {
        $this->authorize('view_index', Note::class);

        $query = Note::query();
        $query->dataTableSelectRows()
            ->dataTableSetJoins()
            ->dataTablePreFilter()
            ->dataTableGroupBy();

        $table = Datatables::of($query);
        $table = Note::dataTableFilterColumns($table);

        if (!request('export')) {
            $table = Note::dataTableEditColumns($table);

            return $table->make(true);
        }

        Note::dataTableExport($table);

        return response()->json([
            'success' => true,
            'message' => __("The export will run in background! When it's done we will notify you via email!")
        ]);
    }

    public function storeFromHomepage(Request $request)
    {
        $this->authorize('create', Note::class);

        if ($request->input('textarea-notes')) {
            $data = [
                'widget_id' => $request->input('widget_id'),
                'user_id' => auth()->user()->id,
                'notes' => $request->input('textarea-notes'),
            ];

            Note::create($data);
        }

        return redirect()->route(
            'admin.homepage',
            [
                'salesChannel' => $request->input('salesChannel') ? $request->input('salesChannel') : '-',
                'salesChannelCompare' => $request->input('salesChannelCompare') ? $request->input('salesChannelCompare') : '-',
                'daterange' => $request->input('daterange') ? str_replace('/', '@', $request->input('daterange')) : null,
                'daterangeCompare' => $request->input('daterangeCompare') ? str_replace('/', '@', $request->input('daterangeCompare')) : null,
            ]
        );
    }
}
