<?php
namespace App\Http\Controllers;

use App\Http\Requests\GetByDateRequest;
use App\Http\Requests\GetHistoryRequest;
use App\Repositories\HistoryRepository;
use Carbon\Carbon;
use Doctrine\ORM\NoResultException;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{

    private $repository;

    public function __construct(HistoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 
     * @param GetByDateRequest $request
     * @return \App\Entities\History|NULL
     */
    public function getByDate(GetByDateRequest $request)
    {
        $history = null;
        $date = Carbon::parse(trim($request->get('date')));
        
        try {
            $history = $this->repository->getByDate($date);
        } catch (NoResultException $e) {
            Log::debug($e->getMessage());
        } catch (\Exception $e) {
            Log::error($e);
        }
        
        return $history;
    }

    /**
     * 
     * @param GetHistoryRequest $request
     * @return array
     */
    public function getHistory(GetHistoryRequest $request) : array
    {
        $histories = [];
        $lastDays = trim($request->get('lastDays'));
        
        try {
            $histories = $this->repository->getHistory($lastDays);
        } catch (\Exception $e) {
            Log::error($e);
        }
        
        return $histories;
    }
}
