<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\ModelMapper as Mapper;


class CommentTest extends TestCase
{


    /**
     * create comment test.
     *
     * @return void
     */
    public function test_create_comment()
    {
        $id = DB::table('posts')->insertGetId(
            [
                'title' => 'Lorem Ipsum',
                'content' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? ',
                'image_url' => 'https://dummyimage.com/640x360/fff/aaa',
            ]
        );
        $data = ['post_id' => $id, 'author' => 'Aloware', 'content' => 'test comment'];
        $response = $this->postJson('/api/comment', $data);
        $response
            ->assertStatus(201)
            ->assertJson(
                [
                    'data' => [
                        'post_id' => $id,
                        'author' => 'Aloware',
                        'content' => 'test comment',
                        'parent_id' => null,
                    ]
                ]
            );
    }

    /**
     * update comment test.
     *
     * @return void
     */
    public function test_update_comment()
    {
        $id = DB::table('posts')->insertGetId(
            [
                'title' => 'Lorem Ipsum',
                'content' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? ',
                'image_url' => 'https://dummyimage.com/640x360/fff/aaa',
            ]
        );
        $commentId = Mapper::createComment(['post_id' => $id, 'author' => 'test author', 'content' => 'test content', 'parent_id' => null]);

        $data = ['id' => $commentId, 'author' => 'Aloware test', 'content' => 'test comment last'];
        $response = $this->putJson('/api/comment', $data);
        $response
            ->assertStatus(200)
            ->assertJson(
                [
                    'data' => [
                        'author' => 'Aloware test',
                        'content' => 'test comment last',

                    ]
                ]
            );
    }

    /**
     * delete comment test.
     *
     * @return void
     */
    public function test_delete_comment()
    {
        $id = DB::table('posts')->insertGetId(
            [
                'title' => 'Lorem Ipsum',
                'content' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? ',
                'image_url' => 'https://dummyimage.com/640x360/fff/aaa',
            ]
        );
        $commentId = Mapper::createComment(['post_id' => $id, 'author' => 'test author', 'content' => 'test content', 'parent_id' => null]);


        $response = $this->deleteJson('/api/comment', ['id' => $commentId]);
        $response->assertStatus(405);
    }


}
