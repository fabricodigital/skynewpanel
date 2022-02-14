<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreMessageRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateMessageRequest;
use App\Models\Admin\MessengerTopic;
use App\Models\Admin\Role;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MessengerController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', MessengerTopic::class);

        $helpRolesNames = config('main.help_roles');

        $roles = Role::whereIn('name', $helpRolesNames)
            ->get();

        return view('admin.messenger.create', compact('roles'));
    }

    /**
     * @param StoreMessageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreMessageRequest $request)
    {
        if(request('type') === 'direct') {
            $this->authorize('create_direct', MessengerTopic::class);
        }else{
            $this->authorize('create_help', MessengerTopic::class);
        }

        $sender = Auth::user()->id;
        $sent_at = Carbon::now();
        $topic = MessengerTopic::create([
            'subject' => request('subject'),
            'sender_id' => $sender,
            'receiver_id' => request('type') === 'direct' ? request('receiver') : null,
            'role_id' => request('type') === 'help' ? request('receiver') : null,
            'type' => request('type'),
            'sent_at' => $sent_at,
        ]);

        $message = $topic->messages()->create([
            'sender_id' => $sender,
            'receiver_id' => request('type') === 'direct' ? request('receiver') : null,
            'receiver_model' => request('receiver_model'),
            'role_id' => request('type') === 'help' ? request('receiver') : null,
            'content' => request('content'),
            'sent_at' => $sent_at,
        ]);

        if($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                $message->addMedia($attachment)->toMediaCollection('attachments');
            }
        }


        return redirect()->route('admin.messenger.outbox')
            ->with('success', __('Message sent successfully!'));
    }

    /**
     * @param \App\Models\Admin\MessengerTopic $topic
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(MessengerTopic $topic)
    {
        $this->authorize('view', $topic);

        $topic->load(['messages' => function($query) {
            $query->with('media');
            $query->with('sender');
        }]);

        $topic->load('roleReceiver');

        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
        header("Pragma: no-cache"); // HTTP 1.0.
        header("Expires: 0");

        return view('admin.messenger.show', compact('topic'));
    }

    /**
     * @param UpdateMessageRequest $request
     * @param \App\Models\Admin\MessengerTopic $topic
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateMessageRequest $request, MessengerTopic $topic)
    {
        $this->authorize('update', $topic);

        $message = $topic->replay($request->get('content'));

        if($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                $message->addMedia($attachment)->toMediaCollection('attachments');
            }
        }

        return redirect()->route('admin.messenger.show', $topic)
            ->with('success', __('Message sent successfully!'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function inbox()
    {
        $this->authorize('view_index', MessengerTopic::class);

        $topicsPerPage = 20;

        switch (request('type')) {
            case 'help':
                $topics = MessengerTopic::orderBy('sent_at', 'desc')->inboxAll()->help()->paginate($topicsPerPage);
                break;
            case 'direct':
                $topics = MessengerTopic::orderBy('sent_at', 'desc')->inboxAll()->direct()->paginate($topicsPerPage);
                break;
            default:
                $topics = MessengerTopic::orderBy('sent_at', 'desc')->inboxAll()->withSheets()->paginate($topicsPerPage);
                break;
        }

        $topics->load('roleReceiver');

        $title  = 'Inbox';

        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
        header("Pragma: no-cache"); // HTTP 1.0.
        header("Expires: 0");

        return view('admin.messenger.index', compact('topics', 'title'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function outbox()
    {
        $this->authorize('view_index', MessengerTopic::class);

        $topicsPerPage = 20;
        switch (request('type')) {
            case 'help':
                $topics = MessengerTopic::orderBy('sent_at', 'desc')->outboxAll()->withSheets()->help()->paginate($topicsPerPage);
                break;
            case 'direct':
                $topics = MessengerTopic::orderBy('sent_at', 'desc')->outboxAll()->withSheets()->direct()->paginate($topicsPerPage);
                break;
            default:
                $topics = MessengerTopic::orderBy('sent_at', 'desc')->outboxAll()->withSheets()->paginate($topicsPerPage);
                break;
        }

        $topics->load('roleReceiver');

        $title  = 'Outbox';

        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
        header("Pragma: no-cache"); // HTTP 1.0.
        header("Expires: 0");

        return view('admin.messenger.index', compact('topics', 'title'));
    }

    /**
     * @param MessengerTopic $topic
     * @return \Illuminate\Http\JsonResponse
     */
    public function read(MessengerTopic $topic)
    {
        if(request()->wantsJson() || request()->ajax()) {
            $topic->makeMessagesAsRead();

            $unreadMessages = Auth::user()->unreadMessages();

            return response()->json([
                'success' => true,
                'unreadMessagesCount' => [
                    'direct' => filter_direct_messages_count($unreadMessages),
                    'help' => filter_help_messages_count($unreadMessages),
                ],
                'infoMessage' => __('You have :num new messages', ['num' => count($unreadMessages)]),
                'topicId' => $topic->id
            ], 201);
        }

        abort(401);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function ajaxGetUnreadTopics()
    {
        $unreadTopics = app()->make('unreadTopics');
        $unreadMessages = app()->make('unreadMessages');

        return response()->json([
            'success' => true,
            'unreadTopics' => $unreadTopics,
            'infoMessage' => __('You have :num new messages', ['num' => count($unreadMessages)]),
        ], 201);
    }
}
