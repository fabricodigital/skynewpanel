<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Discussion;
use App\Rules\EditorRequired;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscussionController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function ajaxGetComments(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'model_type' => 'required',
            'model_id' => 'required',
            'perPage' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->all()]);
        }
        $params = $validator->validated();
        $modelClass = $params['model_type'];
        $model = $modelClass::find($params['model_id']);

        $this->authorize('view_comments', $model);

        $comments = $model->getPaginatedComments($params['perPage']);

        return response()
            ->json([
                'success' => true,
                'data' => $comments
            ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function ajaxSaveComment(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'comment' => new EditorRequired(),
            'model_type' => 'required',
            'model_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->all()]);
        }

        $data = $validator->validated();

        $modelClass = $data['model_type'];
        $model = $modelClass::find($data['model_id']);

        $this->authorize('create_comment', $model);

        $data['creator_id'] = Auth::user()->id;

        $model->discussions()->create($data);
        $model->touch();

        $comments = $model->getPaginatedComments(request('perPage'));

        return response()->json([
            'success' => true,
            'data' => $comments,
            'message' => __('Comment added successfully!')
        ]);
    }
}
