<?php declare(strict_types=1);

namespace Swag\ExtendPage\Storefront\Subscriber;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\CountAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\AggregationResult\CountResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Storefront\Pagelet\Footer\FooterPageletLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FooterSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityRepositoryInterface
     */
    private $productRepository;

    public function __construct(EntityRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
          FooterPageletLoadedEvent::class => 'addActiveProductCount'
        ];
    }

    public function addActiveProductCount(FooterPageletLoadedEvent $event): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('product.active', true));
        $criteria->addAggregation(new CountAggregation('product.id', 'productCount'));

        /** @var CountResult $productCountResult */
        $productCountResult = $this->productRepository
            ->aggregate($criteria, $event->getContext())
            ->getAggregations()
            ->get('productCount')
            ->getResult()[0];

        $event->getPagelet()->addExtension('product_count', $productCountResult);
    }
}
