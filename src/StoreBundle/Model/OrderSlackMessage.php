<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 05.03.16
 * Time: 18:52
 */

namespace StoreBundle\Model;

use Slack\Model\Message;
use Slack\Model\MessageField;
use StoreBundle\Entity\Order;

/**
 * Class OrderSlackMessage
 *
 * @package StoreBundle\Model
 */
class OrderSlackMessage extends Message
{
    protected $username = 'Order';

    /**
     * OrderSlackMessage constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->text = sprintf(
            'Заказ №%s на сумму %s UAH.' . PHP_EOL,
            $order->getId(),
            $order->getCart()->getSum()
        );

        $user = $order->getUser();

        $this->setFields([
            new MessageField('Name', $user->getFirstname(), true),
            new MessageField('Email', $user->getEmail(), true),
            new MessageField('Phone', $user->getPhone(), true)
        ]);
    }
}