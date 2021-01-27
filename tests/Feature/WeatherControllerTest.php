<?php
namespace Tests\Feature;

use App\Repositories\HistoryRepository;
use Tests\TestCase;
use Carbon\Carbon;
use Upgate\LaravelJsonRpc\Server\ErrorCode;

class WeatherControllerTest extends TestCase
{

    /**
     *
     * @var \App\Repositories\HistoryRepository
     */
    private $repository;
    
    /**
     *
     * {@inheritdoc}
     * @see \Illuminate\Foundation\Testing\TestCase::setUp()
     */
    protected function setUp(): void
    {
        if (! $this->app) {
            $this->refreshApplication();
        }

        $this->repository = $this->app->make(HistoryRepository::class);
        $this->setUpHasRun = true;
    }
    
    public function testGetByIncorrectFormatDate()
    {
        $data = [
            'jsonrpc' => '2.0',
            'method' => 'weather.getByDate',
            'params' => [
                'date' => '2020-1130'
            ],
            'id' => 1
        ];
        
        $response = $this->postJson('/jsonrpc', $data);
        $response->assertStatus(200);
        $response->assertJson([
            'jsonrpc' => '2.0',
            'id' => 1,
            'error' => [
                'code' => ErrorCode::INVALID_PARAMS
            ]
        ]);
    }
    
    public function testGetByFutureDate()
    {
        $data = [
            'jsonrpc' => '2.0',
            'method' => 'weather.getByDate',
            'params' => [
                'date' => Carbon::today()->addDay()->format('Y-m-d')
            ],
            'id' => 1
        ];
        
        $response = $this->postJson('/jsonrpc', $data);
        $response->assertStatus(200);
        $response->assertJson([
            'jsonrpc' => '2.0',
            'id' => 1,
            'error' => [
                'code' => ErrorCode::INVALID_PARAMS
            ]
        ]);
    }
    
    public function testGetByDate()
    {
        $data = [
            'jsonrpc' => '2.0',
            'method' => 'weather.getByDate',
            'params' => [
                'date' => Carbon::today()->addMonths(-3)->format('Y-m-d')
            ],
            'id' => 1
        ];

        $response = $this->postJson('/jsonrpc', $data);
        $response->assertStatus(200);
        $response->assertJson([
            'jsonrpc' => '2.0',
            'id' => 1,
            'result' => []
        ]);
    }
    
    public function testGetHistoryByNegativeDays()
    {
        $data = [
            'jsonrpc' => '2.0',
            'method' => 'weather.getHistory',
            'params' => [
                'lastDays' => -30
            ],
            'id' => 1
        ];
        
        $response = $this->postJson('/jsonrpc', $data);
        $response->assertStatus(200);
        $response->assertJson([
            'jsonrpc' => '2.0',
            'id' => 1,
            'error' => [
                'code' => ErrorCode::INVALID_PARAMS
            ]
        ]);
    }
    
    public function testGetHistoryByIncorrectType()
    {
        $data = [
            'jsonrpc' => '2.0',
            'method' => 'weather.getHistory',
            'params' => [
                'lastDays' => "error"
            ],
            'id' => 1
        ];
        
        $response = $this->postJson('/jsonrpc', $data);
        $response->assertStatus(200);
        $response->assertJson([
            'jsonrpc' => '2.0',
            'id' => 1,
            'error' => [
                'code' => ErrorCode::INVALID_PARAMS
            ]
        ]);
    }
    
    public function testGetHistory()
    {
        $data = [
            'jsonrpc' => '2.0',
            'method' => 'weather.getHistory',
            'params' => [
                'lastDays' => 30
            ],
            'id' => 1
        ];
        
        $response = $this->postJson('/jsonrpc', $data);
        $response->assertStatus(200);
        $response->assertJson([
            'jsonrpc' => '2.0',
            'id' => 1,
            'result' => []
        ]);
    }

}
