<?php


// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Avatar;
use App\Models\Phone;
use App\Models\PhoneBrand;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    //НЕ ЧИСТИТ БАЗУ ДАННЫХ
    /**
     * A basic test example.
     */

    public function test_get_list_and_pagination(): void
    {
        $authUser= User::query()->where('name', 'Always Auth User')->first();
        $response = $this->actingAs($authUser)->get(route('api.users.index'));
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

        $response = $this->actingAs($authUser)->get(route('api.users.index', [
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
        $authUser= User::query()->where('name', 'Always Auth User')->first();
        $responseFilter = $this->actingAs($authUser)->get('/api/users?' . $filter);
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

        $responseFilter = $this->actingAs($authUser)->get('/api/users?' . $filter);
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
        $response = $this->actingAs($authUser)->get('/api/users?' . $filter);
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

    public function test_create_user(): void
    {
        Storage::fake('public');
        $chars = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ';
        $charsArray = preg_split('//u', $chars, -1, PREG_SPLIT_NO_EMPTY);
        $charsLength = count($charsArray);
        $name = '';
        for ($i = 0; $i < 16; $i++) {
            $name = $name . $charsArray[random_int(0, $charsLength - 1)];
        }
        $name = Str::ucfirst($name);
        $email = Str::random(16) . '@gmail.com';
        $password = Str::random(16);
        $slug = Str::slug($name);
        $realFilePath = 'D:\images.jpeg';

        if (!file_exists($realFilePath)) {
            // Если файла нет - создаем простой текстовый файл
            $tempPath = tempnam(sys_get_temp_dir(), 'avatar') . '.jpeg';
            file_put_contents($tempPath, 'fake image content');
            $realFilePath = $tempPath;
        }
        $avatar = new UploadedFile(
            $realFilePath,              // путь к файлу
            'images.jpeg',              // оригинальное имя
            'image/jpeg',               // mime тип
            null,                       // размер (определится автоматически)
            true                        // тестовый режим
        );
        $data = [
            'avatar' => $avatar,
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ];
        $authUser = User::query()->where('name', 'Always Auth User')->first();

        $response = $this->actingAs($authUser)
            ->post(route('api.users.store'), $data);


        dump("Status: " . $response->getStatusCode());
        $response->assertStatus(201);

            $user = User::query()
                //->where('email', $email)
                ->where('slug', $slug)
                //->where('name', $name)
                ->get();

            $this->assertNotNull($user);
            $this->assertNotNull($user[0]->avatar);

            $this->assertCount(1, $user);
            $this->assertEquals($user[0]->name, $name);
            $this->assertEquals($user[0]->email, $email);
            $this->assertEquals($user[0]->slug, $slug);
            $this->assertTrue(Hash::check($password, $user[0]->password));



    }

    public function test_update_user(): void
    {
        Storage::fake('public');
        $phoneBrand = PhoneBrand::factory()->create();
        $user = User::factory()->create();
        $phone = Phone::factory([
            'phone_brand_id' => $phoneBrand->id,
            'user_id'=>$user->id,
        ])->create()->toArray();

        $chars = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ';
        $charsArray = preg_split('//u', $chars, -1, PREG_SPLIT_NO_EMPTY);
        $charsLength = count($charsArray);
        $name = '';
        for ($i = 0; $i < 16; $i++) {
            $name = $name . $charsArray[random_int(0, $charsLength - 1)];
        }
          $name=Str::ucfirst($name);
        $email = Str::random(16) . '@gmail.com';
        $password = Str::random(16);
        $realFilePath = 'D:\imagesUpdated.jpg';

        if (!file_exists($realFilePath)) {
            // Если файла нет - создаем простой текстовый файл
            $tempPath = tempnam(sys_get_temp_dir(), 'avatar') . '.jpg';
            file_put_contents($tempPath, 'fake image content');
            $realFilePath = $tempPath;
        }
        $avatar = new UploadedFile(
            $realFilePath,              // путь к файлу
            'imagesUpdated.jpg',              // оригинальное имя
            'image/jpeg',               // mime тип
            null,                       // размер (определится автоматически)
            true                        // тестовый режим
        );
        $data =[
            'avatar' => $avatar,
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ];
        $authUser= User::query()->where('name', 'Always Auth User')->first();
        $this->assertNotNull($authUser);
        $response = $this->actingAs($authUser)->patch(route('api.users.update' ,['user'=>$user->slug]),$data);
        dump("Status: " . $response->getStatusCode());

        $user->refresh();
        $responseData = $response->json('data');
        $this->assertNotNull($responseData);
        $this->assertEquals(sort($phone),sort( $responseData['phones']));

        $response->assertStatus(200);
        $this->assertEquals(sort($phone),sort( $response->json('data')['phones']));

        $dbUser =User::query()
            ->where('id',$user->id)
            ->where('email', $email)
            ->where('slug', $user->slug)
            ->where('name', $name)
            ->get();
        $this->assertCount(1, $dbUser);
        $this->assertNotNull($dbUser);
        $this->assertEquals($dbUser[0]->name, $name);
        $this->assertEquals($dbUser[0]->email, $email);
        $this->assertEquals($dbUser[0]->slug, $user->slug);
        $this->assertTrue(Hash::check($password,$dbUser[0]->password));

        $this->assertNotNull($dbUser[0]->avatar);
        $this->assertEquals(1, User::where('email', $email)->count());

    }

    public function test_delete_user(): void
    {
        $user = User::factory()->create();

        $authUser= User::query()->where('name', 'Always Auth User')->first();
        $this->assertNotNull($authUser);
        $response = $this->actingAs($authUser)->delete(route('api.users.destroy',$user));
        $response->assertStatus(200);
        $this->assertNull(User::query()->where('slug', $user->slug)->first());
    }

}
