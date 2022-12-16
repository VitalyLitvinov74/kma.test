<?php
declare(strict_types=1);

namespace app\objects\queue;

use app\objects\forms\IField;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class QueueRabbitMq
{
    private $queueName;

    private $exchangeName; //not used

    private $connection;

    /**
     * @param IField $exchangeName
     * @param IField $queueName
     * @throws \Exception
     */
    public function __construct(IField $exchangeName, IField $queueName)
    {
        $this->connection = new AMQPStreamConnection(
            'kma.test',
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
            $this->queueName->toString()
        );
        $channel->close();
    }

    /**
     * что то получаем из очереди
     * @param callable $needleMake - функиця обратного вызова, в которую передается сообщение из очереди
     * @return void
     */
    public function processMessages(callable $needleMake): void
    {
        $channel = $this->channel();
        $channel->basic_qos(
            null,
            1,
            null

        );

        $channel->basic_consume(
            $this->queueName,
            '',
            false,
            false,
            false,
            false,
            $needleMake //функция обратного вызова, для обработки сообщения
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
            $this->queueName->toString(),
            false,
            true,
            false,
            false,
        );
        return $channel;
    }
}