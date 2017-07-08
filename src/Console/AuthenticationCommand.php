<?php

namespace Twitter\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Twitter\API\Exception\TwitterException;
use Twitter\API\REST\TwitterApiGateway;

/**
 * Class AuthenticationCommand
 *
 * @package TwitterStream\Console
 *
 * @codeCoverageIgnore
 */
class AuthenticationCommand extends Command
{
    /** @var TwitterApiGateway */
    private $adapter;

    /**
     * AuthenticationCommand constructor.
     *
     * @param TwitterApiGateway $adapter
     *
     * @throws LogicException
     */
    public function __construct(TwitterApiGateway $adapter, $name = null)
    {
        parent::__construct($name);

        $this->adapter = $adapter;
    }

    /**
     * Configures the command
     */
    protected function configure()
    {
        $this->setDescription('Authenticate');
    }

    /**
     * Code executed when command invoked
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     *
     * @return void
     *
     * @throws RuntimeException
     * @throws LogicException
     * @throws InvalidArgumentException
     * @throws TwitterException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $authUrl = $this->adapter->getAuthenticationUrl();

        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $verificationCode = $helper->ask(
            $input,
            $output,
            new Question('Visit <info>' . $authUrl . '</info> and enter verification code: ')
        );

        $authToken = $this->adapter->getAuthenticationToken($verificationCode);

        $output->writeln('Token  : <info>' . $authToken->getToken() . '</info>');
        $output->writeln('Secret : <info>' . $authToken->getSecret() . '</info>');
    }
}
