<?php


// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_get_list_and_pagination(): void
    {

        $response = $this->get(route('api.users.index'));
        $data = $response->json()['data'];
        $paginatedData = $response->json()['meta'];
        $userCount = User::query()->count();

//        dd($userCount);
        $response->assertStatus(200);
        $this->assertEquals(count($data), 10);
        $this->assertArrayHasKey('last_page', $paginatedData);
        $this->assertArrayHasKey('from', $paginatedData);
        $this->assertArrayHasKey('current_page', $paginatedData);
        $this->assertArrayHasKey('links', $paginatedData);
        $this->assertArrayHasKey('path', $paginatedData);
        $this->assertArrayHasKey('per_page', $paginatedData);
        $this->assertArrayHasKey('to', $paginatedData);
        $this->assertArrayHasKey('total', $paginatedData);
        $this->assertEquals($userCount, $paginatedData['total']);

        $perPage = 100;
        $page =3;

        $response = $this->get(route('api.users.index',[
            'page'=>$page,
            'per_page'=>$perPage,
        ]));

        $paginatedData = $response->json()['meta'];
        $this->assertEquals($paginatedData['per_page'], $perPage);
        $this->assertEquals($paginatedData['current_page'], $page);

        $filter = http_build_query([
            'search'=> 'Always Auth User',
            ]);
        $responseFilter = $this->get('/api/users?' . $filter);
        $responseFilter->assertStatus(200);
        $filterData = $response->json()['data'];

        dd($filterData);



}
}
