<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Exceptions\MaximumDepthException as MaxException;
use App\Models\ModelMapper as Mapper;
use App\Models\ModelMapper;

final class PostController extends Controller
{
    /**
     * Get a single post including comments ordered by the latest
     *
     * @return Array
     */
    public function index($id=null)
    {
        if($id===null)
        {
            return response()->json([
                "success" => false,
                "message" => "Not Found Resources",
                "data" => null
            ],404);
        }

        $mapper = new Mapper();
        $myPosts = $mapper->singlePostComments($id);

        return response()->json([
            "success" => true,
            "message" => "Successfully fetch the resources",
            "data" => $myPosts
        ],200);

    }

    /**
     * Validate and insert a single comment
     *
     * @return bool
     */
    public function CreateComment(CreateCommentRequest $request)
    {

        if ($request->parent_id !== null) {

            $mapper = new Mapper();
            $parentDepth = $mapper->FindDepth($request->post_id, $request->parent_id);

            if ($parentDepth >= config('app.maxdepth')) {
                throw new MaxException();
            }
        }

        $willbeInserted = [
            'post_id' => $request->post_id,
            'parent_id' => $request->parent_id,
            'author' => $request->author,
            'content' => $request->content,
        ];


        $id=Mapper::createComment($willbeInserted);

        if($id !==null){

            return response()->json([
                "success" => true,
                "message" => "Comment inserted successfully.",
                "data" => $willbeInserted
            ],201);

        }
    }

    public function DeleteComment($id)
    {
       $comment=ModelMapper::singleComment($id);

        if($comment===null){
            return response()->json([
                "success" => false,
                "message" => "Not Found Resources",
                "data" => null
            ],404);
        }

        //if there exists sibling not allow to delete
        $count = $comment=ModelMapper::isParent($comment);

        if($count!==0){
            return response()->json([
                "success" => false,
                "message" => "Please delete the child comments first",
                "data" => null
            ],422);
        }

        $deleted = Mapper::deleteComment($id);

        if($deleted>0){
            return response()->json([
                "success" => true,
                "message" => "Comment deleted successfully.",
                "data" => $comment
            ],405);
        }

    }

    public function UpdateComment(UpdateCommentRequest $request)
    {
        if ($request->validated()) {

            $WillbeUpdated = [
                'author' => $request->author,
                'content' => $request->content,
            ];

            $affected = Mapper::updateComment($request->id,$WillbeUpdated);

            if($affected>0){

                return response()->json([
                    "success" => true,
                    "message" => "Comment updated successfully.",
                    "data" => $WillbeUpdated
                ]);
            }

            return response()->json([
                "success" => false,
                "message" => "Nothing changed.",
                "data" => $WillbeUpdated
            ],422);

        }
    }

}
