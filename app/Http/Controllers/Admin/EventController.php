<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreEventRequest;
use App\Http\Requests\Admin\UpdateEventRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Admin\Event;

class EventController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if(request()->wantsJson() || request()->ajax()) {
            return response()->json(['events' => Event::getForPeriod()]);
        }

        abort(404);
    }

    /**
     * @param StoreEventRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreEventRequest $request)
    {
        if($request->wantsJson() || $request->ajax()) {
            $this->authorize('create', Event::class);

            $data = $request->validated();
            $data ['creator_id']= Auth::id();
            $data['start'] = Carbon::createFromFormat('d/m/Y H:i', $data['start']);
            $data['end'] = Carbon::createFromFormat('d/m/Y H:i', $data['end']);

            $event = Event::createTranslated($data);

            $roleIds = $request->get('roles')? $request->get('roles') : array();
            $event->roles()->sync($roleIds);

            $usersIds = $request->get('users') ? $request->get('users') : array();
            $event->users()->sync($usersIds);

            if($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $attachment) {
                    $event->addMedia($attachment)->toMediaCollection('attachments');
                }
            }

            $event->load('roles', 'users', 'media');
            $event->media_template = view('partials._attachments', ['item' => $event, 'canMediaBeenDeleted' => true])->render();

            return response()->json([
                'created' => true,
                'event' => $event,
            ], 201);
        }

        abort(401);
    }

    /**
     * @param UpdateEventRequest $request
     * @param Event $event
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        if($request->wantsJson() || $request->ajax()) {

            $this->authorize('update', $event);

            $data = $request->validated();
            $data['start'] = Carbon::createFromFormat('d/m/Y H:i', $data['start']);
            $data['end'] = Carbon::createFromFormat('d/m/Y H:i', $data['end']);
            $event->updateTranslated($data);

            $event->revisionableUpdateManyToMany($data);

            $roleIds = $request->get('roles')? $request->get('roles') : array();
            $event->roles()->sync($roleIds);

            $usersIds = $request->get('users') ? $request->get('users') : array();
            $event->users()->sync($usersIds);

            if($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $attachment) {
                    $event->addMedia($attachment)->toMediaCollection('attachments');
                }
            }

            $event->load('roles', 'users', 'media');
            $event->media_template = view('partials._attachments', ['item' => $event, 'canMediaBeenDeleted' => true])->render();

            return response()->json([
                'updated' => true,
                'event' => $event,
            ], 201);
        }

        abort(401);
    }

    /**
     * @param Event $event
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Event $event)
    {
        if(request()->wantsJson() || request()->ajax()) {
            $this->authorize('delete', $event);

            $event->delete();

            return response()->json([
                'deleted' => true,
                'event' => $event,
            ], 201);
        }

        abort(401);
    }

    /**
     * @param Event $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function read(Event $event)
    {
        if(request()->wantsJson() || request()->ajax()) {
            $event->read();
            return response()->json(['success' => true], 201);
        }

        abort(401);
    }
}
