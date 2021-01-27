<?php
namespace Tests\Unit;

use App\Entities\History;
use App\Repositories\HistoryRepository;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class HistoryRepositoryTest extends TestCase
{

    /**
     *
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $em;

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

        $this->em = $this->app->make(EntityManagerInterface::class);
        $this->repository = new HistoryRepository($this->em);
        $this->setUpHasRun = true;
    }

    public function testSaveTomorrowDate()
    {
        $history = new History();
        $history->setDateAt(Carbon::tomorrow());
        $history->setTemp(24.5);

        try {
            $this->em->persist($history);
            $this->assertFalse(true, 'Date validation is incorrect.');
        } catch (\Exception $e) {
            $this->assertInstanceOf(ValidationException::class, $e);
        }
    }

    public function testSaveOutOfRangeTemperature()
    {
        $history = new History();
        $history->setDateAt(Carbon::yesterday());
        $history->setTemp(60.5);

        try {
            $this->em->persist($history);
            $this->assertFalse(true, 'Temperature range validation is incorrect.');
        } catch (\Exception $e) {
            $this->assertInstanceOf(ValidationException::class, $e);
        }
    }

    public function testSaveIncorrectScaleTemperature()
    {
        $history = new History();
        $history->setDateAt(Carbon::yesterday());
        $history->setTemp(59.11);

        try {
            $this->em->persist($history);
            $this->assertFalse(true, 'Temperature scale validation is incorrect.');
        } catch (\Exception $e) {
            $this->assertInstanceOf(ValidationException::class, $e);
        }
    }

    public function testSaveCorrectDataWithPositiveTemperature()
    {
        $exception = null;
        $history = new History();
        $history->setDateAt(Carbon::yesterday());
        $history->setTemp(24.5);

        try {
            $this->em->persist($history);
        } catch (\Exception $e) {
            $exception = $e;
        }

        $this->assertTrue($exception === null);
    }

    public function testSaveCorrectDataWithNegativeTemperature()
    {
        $exception = null;
        $history = new History();
        $history->setDateAt(Carbon::yesterday());
        $history->setTemp(- 30.5);

        try {
            $this->em->persist($history);
        } catch (\Exception $e) {
            $exception = $e;
        }

        $this->assertTrue($exception === null);
    }

    public function testGetByDate()
    {
        $date = Carbon::today()->addMonths(- 3);
        $result = $this->repository->getByDate($date);
        $this->assertInstanceOf(History::class, $result);
    }

    public function testGetHistory()
    {
        $result = $this->repository->getHistory(30);
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
    }
}
