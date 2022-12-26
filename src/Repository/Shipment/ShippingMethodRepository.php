<?php


namespace App\Repository\Shipment;

use App\Interface\Vendor\VendorInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;



class ShippingMethodRepository extends EntityRepository
{
    public function createListQueryBuilder(string $locale): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.translations', 'translation', 'WITH', 'translation.locale = :locale')
            ->setParameter('locale', $locale)
        ;
    }

    public function findEnabledForChannel(VendorInterface $vendor): array
    {
        return $this->createEnabledForChannelQueryBuilder($vendor)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findEnabledForZonesAndChannel(array $zones, VendorInterface $vendor): array
    {
        return $this->createEnabledForChannelQueryBuilder($vendor)
            ->andWhere('o.zone IN (:zones)')
            ->setParameter('zones', $zones)
            ->addOrderBy('o.position', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    protected function createEnabledForChannelQueryBuilder(VendorInterface $vendor): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.enabled = :enabled')
            ->andWhere('o.archivedAt IS NULL')
            ->andWhere(':vendor MEMBER OF o.vendors')
            ->setParameter('vendor', $vendor)
            ->setParameter('enabled', true)
        ;
    }

    public function findByName(string $name, string $locale): array
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.translations', 'translation')
            ->andWhere('translation.name = :name')
            ->andWhere('translation.locale = :locale')
            ->setParameter('name', $name)
            ->setParameter('locale', $locale)
            ->getQuery()
            ->getResult()
            ;
    }
}