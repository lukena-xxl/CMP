<?php


namespace App\EventListener;

use App\Entity\Orders;
use App\Services\UpdateManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

class OrderListener
{
    private $updateManager;
    private $translator;

    public function __construct(Translator $translator, UpdateManager $updateManager)
    {
        $this->updateManager = $updateManager;
        $this->translator = $translator;
    }

    public function postPersist(Orders $order, LifecycleEventArgs $args)
    {
        $message = $this->translator->trans('Создан новый заказ');
        $message .= ': ' . $order->getId() . '; ';
        $message .= $this->translator->trans('Заказчик') . ': ' . $order->getFullName() . ' (' . $order->getCity() . ')';

        $this->updateManager->notifyOfUpdate($message, ['mail', 'telegram']);
    }
}
