<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

final class ModelMapper
{


    function arrayRemove(&$array, &$object)
    {
        if (($key = array_search($object, $array)) !== false) {
            unset($array[$key]);
        }
    }
    function findRoots(&$array, $baseRoot)
    {
        $roots = [];
        foreach ($array as &$element) {
            $cpy = null;
            if (
                ($baseRoot == null && $element['parent_id'] == null) ||
                ($baseRoot !== null && $element['parent_id'] == $baseRoot['id'])
            ) {
                $cpy = $element;
                $this->arrayRemove($array, $element);
                array_push($roots, [
                    'id' => $cpy["id"],
                    'post_id' => $cpy["post_id"],
                    'author' => $cpy["author"],
                    'content' => $cpy["content"],
                    'comments' => $this->findRoots($array, $cpy)
                ]);
            }
        }
        return $roots;
    }

    public function findDepth($postId, $parent_id)
    {

        $biseyler = DB::table('comments')->orderByRaw('updated_at - created_at DESC')
            ->select('id', 'parent_id', 'post_id', 'author', 'content')->get(['post_id' => $postId]);

        $deneme = [];
        foreach ($biseyler as $bisey) {
            $ekle = [];
            $ekle['id'] = $bisey->id;
            $ekle['post_id'] = $bisey->post_id;
            $ekle['parent_id'] = $bisey->parent_id;
            $ekle['author'] = $bisey->author;
            $ekle['content'] = $bisey->content;
            $deneme[] = $ekle;
        }

        $map = array();
        foreach ($deneme as $e)
            $map[$e['id']] = array(
                'parent' => $e['parent_id'],
                'depth' => null
            );

            return $this->depth($map,$parent_id);
    }

    function depth(&$map, $id)
    {
        $depth = 0;
        $the_id = $id;
        while ($map[$id]['parent']) {
            if ($map[$id]['depth'] !== null) {
                $depth += $map[$id]['depth'];
                break;
            }
            $id = $map[$id]['parent'];
            $depth++;
        }
        $map[$the_id]['depth'] = $depth;
        return $depth;
    }

    public function singlePostComments($postId)
    {

        $biseyler = DB::table('comments')->orderByRaw('updated_at - created_at DESC')->where(['post_id' => $postId])
            ->select('id', 'parent_id', 'post_id', 'author', 'content')->get();

        $deneme = [];
        foreach ($biseyler as $bisey) {
            $ekle = [];
            $ekle['id'] = $bisey->id;
            $ekle['post_id'] = $bisey->post_id;
            $ekle['parent_id'] = $bisey->parent_id;
            $ekle['author'] = $bisey->author;
            $ekle['content'] = $bisey->content;
            $deneme[] = $ekle;
        }

        $object = ["comments" => []];
        $roots = $this->findRoots($deneme, null);
        $object["comments"] = $roots;
        return $object;
    }

    public static function singleComment($commentId){
        $comment = DB::table('comments')->orderByRaw('updated_at - created_at DESC')->where(['id' => $commentId])
        ->select('id', 'parent_id', 'post_id', 'author', 'content')->first();

        return $comment;
    }

    public static function isParent($parent){
        $count = DB::table('comments')->orderByRaw('updated_at - created_at DESC')->where(['parent_id' => $parent->id])
        ->select('id', 'parent_id', 'post_id', 'author', 'content')->count();

        return $count;
    }

    public static function deleteComment($id){
        $deleted = DB::table('comments')->where('id', '=', $id)->delete();
        return $deleted;
    }

    public static function updateComment($id,$WillbeUpdated){
        $affected = DB::table('comments')
        ->where('id', $id)
        ->update($WillbeUpdated);
        return $affected;
    }

    public static function createComment($willbeInserted){
        $id = DB::table('comments')->insertGetId(
            $willbeInserted
        );
        return $id;

    }


}
