<?php

namespace Functional\Bundles\ApiBundle\CommandHandler;

use Prophecy\Prophecy\ObjectProphecy;

use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class SendAccountRegistrationEmailHandlerTest extends KernelTestCase
{
    public function testItSendsAccountRegistrationEmailWithHostname(): void
    {
        $container = self::getContainer();

        /** @var TranslatorInterface $translator */
        $translator = $container->get('translator');

        $emailSender = $container->get('email_sender');

        /** @var ChannelRepositoryInterface|ObjectProphecy $channelRepository */
        $channelRepository = $this->prophesize(ChannelRepositoryInterface::class);
        /** @var UserRepositoryInterface|ObjectProphecy $userRepository */
        $userRepository = $this->prophesize(UserRepositoryInterface::class);
        /** @var ChannelInterface|ObjectProphecy $channel */
        $channel = $this->prophesize(ChannelInterface::class);
        /** @var UserInterface|ObjectProphecy $user */
        $user = $this->prophesize(UserInterface::class);

        $user->getUsername()->willReturn('username');
        $user->getEmailVerificationToken()->willReturn('token');

        $channelRepository->findOneByCode('CHANNEL_CODE')->willReturn($channel->reveal());
        $userRepository->findOneByEmail('user@example.com')->willReturn($user->reveal());

        $sendAccountRegistrationEmailHandler = new SendAccountRegistrationEmailHandler(
            $userRepository->reveal(),
            $channelRepository->reveal(),
            $emailSender
        );

        $sendAccountRegistrationEmailHandler(
            new SendAccountRegistrationEmail(
                'user@example.com',
                'en_US',
                'CHANNEL_CODE'
            )
        );

        self::assertEmailCount(1);
        $email = self::getMailerMessage();
        self::assertEmailAddressContains($email, 'To', 'user@example.com');
        self::assertEmailHtmlBodyContains(
            $email,
            $translator->trans('email.user_registration.start_shopping', [], null, 'en_US'),
        );
    }

    public function testItSendsAccountRegistrationEmailWithoutHostname(): void
    {
        $container = self::getContainer();

        /** @var TranslatorInterface $translator */
        $translator = $container->get('translator');

        $emailSender = $container->get('email_sender');

        /** @var ChannelRepositoryInterface|ObjectProphecy $channelRepository */
        $channelRepository = $this->prophesize(ChannelRepositoryInterface::class);
        /** @var UserRepositoryInterface|ObjectProphecy $userRepository */
        $userRepository = $this->prophesize(UserRepositoryInterface::class);
        /** @var ChannelInterface|ObjectProphecy $channel */
        $channel = $this->prophesize(ChannelInterface::class);
        /** @var UserInterface|ObjectProphecy $user */
        $user = $this->prophesize(UserInterface::class);

        $user->getUsername()->willReturn('username');
        $user->getEmailVerificationToken()->willReturn('token');

        $channelRepository->findOneByCode('CHANNEL_CODE')->willReturn($channel->reveal());
        $userRepository->findOneByEmail('user@example.com')->willReturn($user->reveal());

        $sendAccountRegistrationEmailHandler = new SendAccountRegistrationEmailHandler(
            $userRepository->reveal(),
            $channelRepository->reveal(),
            $emailSender
        );

        $sendAccountRegistrationEmailHandler(
            new SendAccountRegistrationEmail(
                'user@example.com',
                'en_US',
                'CHANNEL_CODE'
            )
        );

        self::assertEmailCount(1);
        $email = self::getMailerMessage();
        self::assertEmailAddressContains($email, 'To', 'user@example.com');
        self::assertEmailHtmlBodyContains(
            $email,
            $translator->trans('email.user_registration.start_shopping', [], null, 'en_US'),
        );
    }
}