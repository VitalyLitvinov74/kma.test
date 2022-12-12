<?php
declare(strict_types=1);

namespace app\queue;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class QueueRabbitMq
{
    private $queueName;

    private $exchangeName;

    private $connection;

    /**
     * @throws \AMQPQueueException
     * @throws \AMQPExchangeException
     * @throws \AMQPConnectionException
     */
    public function __construct(string $exchangeName, string $queueName)
    {
        $this->connection = new AMQPStreamConnection(
            'localhost',
            5672,
            'rabbitmq',
            'rabbitmq'
        );
        $this->exchangeName = $exchangeName;
        $this->queueName = $queueName;
    }

    /**
     * Ставит чтото в очередь
     * @param array $data
     * @param int $delaySec
     * @return void
     */
    public function putIn(array $data, int $delaySec = 0): void
    {
        $channel = $this->channel();
        $channel->basic_publish(
            new AMQPMessage(
                json_encode($data),
                [
                    'delivery_mode' => 2,
                    'application_headers' => new AMQPTable([
                        'x-delay' => $delaySec * 1000
                    ])
                ]
            ),
            '',
            $this->queueName
        );
        $channel->close();
    }

    /**
     * что то получаем из очереди
     * @param callable $needleMake - функиця обратного вызова, в которую передается сообщение из очереди
     * @return void
     */
    public function pickUpTo(callable $needleMake): void
    {
        $channel = $this->channel();
        $channel->basic_qos(
            null,     #размер предварительной выборки - размер окна предварительнйо выборки в октетах, null означает “без определённого ограничения”
            1,      #количество предварительных выборок - окна предварительных выборок в рамках целого сообщения
            null    #глобальный - global=null означает, что настройки QoS должны применяться для получателей, global=true означает, что настройки QoS должны применяться к каналу

        );

        $channel->basic_consume(
            'invoice_queue',
            '',
            false,
            false,
            false,
            false,
            array($this, 'process')
        );

        while (count($channel->callbacks)) {
            $channel->wait();
        }
        $channel->close();
    }

    public function __destruct()
    {
        $this->connection->close();
    }

    private function channel(): AMQPChannel
    {
        $channel = $this->connection->channel();
        $channel->queue_declare(
            $this->queueName,
            false,
            true,
            false,
            false,
        );
        return $channel;
    }
}