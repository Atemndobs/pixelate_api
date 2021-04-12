<?php namespace Tests\APIs;


use App\Http\Resources\PostResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\PostData;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Post;

class PostApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, RefreshDatabase, PostData;

    /**
     * @var Post
     */
    public Post $post;
    public function setUp() :void
    {
        parent::setUp();
        $this->refreshDatabase();
        $this->refreshTestDatabase();
        $postData = $this->createPostData();
        $this->post = Post::create($postData);
    }


    /**
     * @test
     */
    public function test_create_post()
    {
        $user = \App\Models\User::factory()->create();
        $post = Post::factory()->make();

        $res = $this->json(
            'POST',
            'http://localhost:8000/api/posts/'.$user->id,
            $post->toArray()
        );

        $response = (json_decode($res->getContent()));

        $responseData = $response->data;

        self::assertSame($res->getStatusCode(), 201);
       self::assertSame($responseData->caption, $post->toArray()['caption']);

    }

    /**
     * @test
     */
    public function test_read_post()
    {
        $post = $this->post;

        $post->created_at = Carbon::yesterday();
        $post->updated_at = Carbon::yesterday();

        $existingPost = json_decode(json_encode(new PostResource($post)));

        $expectedResponse = \File::get('tests/fixtures/postResponseResource.json');
        $expected= json_decode($expectedResponse);

        $this->response = $this->json(
            'GET',
            'http://localhost:8000/api/posts/'.$existingPost->id
        );

        $response = (json_decode($this->response->getContent()));
        $response->data->created_dates->created_at_human = '1 day ago';
        $response->data->created_dates->created_at = '2020-12-30T00:00:00.000000Z';
        $response->data->updated_dates->updated_at_human = '1 day ago';
        $response->data->updated_dates->updated_at = '2020-12-30T00:00:00.000000Z';


        $this->assertApiResponse($post->toArray());
        self::assertEquals($expected->data, $response->data);

    }

    /**
     * @test
     */
    public function test_update_post()
    {
        $post = Post::factory()->create();
        $editedPost = Post::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            'http://localhost:8000/api/posts/'.$post->id,
            $editedPost
        );

        $this->assertApiResponse($editedPost);
    }

    /**
     * @test
     */
    public function test_delete_post()
    {

        $post = Post::factory()->create();

        $this->response = $this->json(
            'DELETE',
             'http://localhost:8000/api/posts/'.$post->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/posts/'.$post->id
        );

        $this->response->assertStatus(404);
    }
}
