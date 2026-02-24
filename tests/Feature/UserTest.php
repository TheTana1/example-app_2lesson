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
        $page = 3;

        $response = $this->get(route('api.users.index', [
            'page' => $page,
            'per_page' => $perPage,
        ]));

        $paginatedData = $response->json()['meta'];
        $this->assertEquals($paginatedData['per_page'], $perPage);
        $this->assertEquals($paginatedData['current_page'], $page);


    }

    public function test_search_filter(): void
    {
        $user = User::query()->get()->random();
        $filter = http_build_query([
            'search' => explode(' ',$user->name)[0],
        ]);
        $responseFilter = $this->get('/api/users?' . $filter);
        $responseFilter->assertStatus(200);
        $filterData = $responseFilter->json()['data'];
        //$this->assertEquals(count($filterData), 1);
        //dd($filterData[0]);
        $this->assertArrayHasKey('id', $filterData[0]);
        $this->assertArrayHasKey('name', $filterData[0]);
        $this->assertArrayHasKey('email', $filterData[0]);
        $this->assertArrayHasKey('age', $filterData[0]);
        $this->assertArrayHasKey('created_at', $filterData[0]);
        $this->assertArrayHasKey('slug', $filterData[0]);
        $this->assertArrayHasKey('active', $filterData[0]);
        $this->assertArrayHasKey('phones', $filterData[0]);
        $this->assertArrayHasKey('avatar', $filterData[0]);

        foreach (array_column($filterData,'name')as $name)
        {
            if(!str_contains($name, explode(' ',$user->name)[0]))
            {
                $this->assertTrue(false);
            }
        }

        $user = User::query()->get()->random();
        $filter = http_build_query([
            'search' => $user->email,
        ]);
        $responseFilter = $this->get('/api/users?' . $filter);
        $responseFilter->assertStatus(200);
        $filterData = $responseFilter->json()['data'];

        foreach (array_column($filterData,'email')as $email)
        {

            if(!str_contains($email, $user->email))
            {
                $this->assertTrue(false);
            }
        }

        $user = User::query()->where('active', 1)->get()->random();
        $filter = http_build_query([
            'active' => $user->active,
        ]);
        $responseFilter = $this->get('/api/users?' . $filter);
        $responseFilter->assertStatus(200);
        $filterData = $responseFilter->json()['data'];

        foreach (array_column($filterData,'active')as $active)
        {
            $this->assertEquals('Active', $active);
        }

        $user = User::query()->where('active', 0)->get()->random();
        $filter = http_build_query([
            'active' => $user->active,
        ]);
        $responseFilter = $this->get('/api/users?' . $filter);
        $responseFilter->assertStatus(200);
        $filterData = $responseFilter->json()['data'];

        foreach (array_column($filterData,'active')as $active)
        {
            $this->assertEquals('Inactive', $active);
        }


        $dateFrom = date('Y-m-d H:i:s', mt_rand(1362055681, 1422055681)); // 2013-2015
        $dateTo = date('Y-m-d H:i:s', mt_rand(1672055681, 1772055681));   // 2023-2026
        $allExpectedUsers = User::where('created_at', '>=', $dateFrom)
            ->where('created_at', '<=', $dateTo)
            ->get();

        $totalExpected = $allExpectedUsers->count();

        $perPage = 10;
        $page = 2;
        $filter = http_build_query([
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'per_page' => $perPage,
            'page' => $page,
        ]);
        $response = $this->get('/api/users?' . $filter);
        $response->assertStatus(200);
        $responseData = $response->json();
        $filteredData = $responseData['data'];

        // Вычисляем ожидаемое количество на текущей странице
        $expectedOnPage = min($perPage, max(0, $totalExpected - ($page - 1) * $perPage));
        $this->assertCount($expectedOnPage, $filteredData, 'Количество элементов на странице не совпадает');

        $dateFromCarbon = \Carbon\Carbon::parse($dateFrom);
        $dateToCarbon = \Carbon\Carbon::parse($dateTo);
        foreach ($filteredData as $user) {
            $createdAt = \Carbon\Carbon::parse($user['created_at']);

            $this->assertTrue(
                $createdAt->between($dateFromCarbon, $dateToCarbon),
                "Дата {$user['created_at']} не в диапазоне {$dateFrom} - {$dateTo}"
            );
        }




//        $this->assertEquals('Always Auth User', $filterData[0]['name']);
//        $this->assertEquals('always@auth.com', $filterData[0]['email']);
//        $this->assertEquals(30, $filterData[0]['age']);
//        $this->assertEquals('2026-02-21T19:41:55.000000Z', $filterData[0]['created_at']);
//        $this->assertEquals('name', $filterData[0]['slug']);
//        $this->assertEquals('Inactive', $filterData[0]['active']);
//        $this->assertEquals([], $filterData[0]['phones']);
//        $this->assertEquals(null, $filterData[0]['avatar']);


    }
}
