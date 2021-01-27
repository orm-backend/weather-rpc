<?php
namespace Database\Seeders;

use App\Entities\History;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Illuminate\Database\Seeder;

class HistorySeeder extends Seeder
{

    /**
     *
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->em->beginTransaction();
        $faker = Factory::create();
        $endDate = CarbonImmutable::yesterday();
        $date = Carbon::yesterday()->addMonths(- 6);

        try {
            while ($date->diffInDays($endDate) > 0) {
                $entity = new History();
                $entity->setDateAt($date->copy());
                $entity->setTemp($faker->randomFloat(1, - 60, 60));
                $this->em->persist($entity);
                $date->addDay();
            }

            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }
    }
}
