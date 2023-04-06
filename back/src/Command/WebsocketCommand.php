<?php

namespace App\Command;

use App\Websocket\MessageHandler;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:websocket',
    description: 'Init websocket real time connection',
)]
class WebsocketCommand extends Command
{
    protected function configure(): void
    {
        $this->setHelp('Init websocket real time connection');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        IoServer::factory(
            new HttpServer(new WsServer(new MessageHandler())),
            3001,
        )
            ->run()
        ;

        return Command::SUCCESS;
    }
}
