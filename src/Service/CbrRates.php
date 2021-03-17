<?php


namespace App\Service;


use App\Entity\CurrencyRates;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;


/**
 * Class CbrRates
 * Provide access to currency rates provided by CBR
 *
 * @package App\Service
 */
class CbrRates
{

    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * CbrRates constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Return all rates stored in database
     * @return array
     */
    public function getRates(): array
    {
        $result = [];

        /** @var CurrencyRates $item */
        foreach ($this->entityManager->getRepository(CurrencyRates::class)->findAll() as $item) {
            $result[] = $item->getArray();
        }

        return $result;
    }

    /**
     * Update rates in database
     *
     * @param array $rates
     * @throws \Doctrine\ORM\ORMException
     */
    public function updateRates(array $rates): void
    {
        $time = new DateTime();
        $repository = $this->entityManager->getRepository(CurrencyRates::class);

        foreach ($rates as $name => $rate) {
            $entity = $repository->findOneBy(['name' => $name]) ?? (new CurrencyRates())->setName($name);

            $entity->setRate($rate);
            $entity->setUpdatedAt($time);

            try {
                $this->entityManager->persist($entity);
                $this->entityManager->flush();
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
    }
}