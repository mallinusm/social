<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

/**
 * Trait AssertsNotifications
 * @package Tests\Feature
 */
trait AssertsNotifications
{
    /**
     * @param string $notificationClass
     * @param MailMessage $mailMessage
     */
    public function assertNotificationHasMail(string $notificationClass, MailMessage $mailMessage): void
    {
        /* @var TestCase $this*/
        $notification = (new Collection($this->dispatchedNotifications))->filter(
            function(array $notification) use ($notificationClass): bool {
                return $notification['instance'] instanceof $notificationClass;
            }
        )->first();

        $this->assertNotNull($notification);
        $this->assertArrayHasKey('instance', $notification);
        $notification = $notification['instance'];
        $this->assertTrue($notification instanceof Notification);

        $this->assertTrue(method_exists($notification, 'via'));
        /** @noinspection PhpUndefinedMethodInspection */
        $this->assertArraySubset(['mail'], $notification->via());

        $this->assertTrue(method_exists($notification, 'toMail'));
        /* @noinspection PhpUndefinedMethodInspection */
        $notificationMail = $notification->toMail();
        $this->assertTrue($notificationMail instanceof MailMessage);
        /* @var MailMessage $notificationMail */
        $this->assertEquals($notificationMail->subject, $mailMessage->subject);
        $this->assertEquals($notificationMail->greeting, $mailMessage->greeting);
        $this->assertEquals($notificationMail->introLines, $mailMessage->introLines);
        $this->assertEquals($notificationMail->outroLines, $mailMessage->outroLines);
        $this->assertEquals(
            parse_url($notificationMail->actionText, PHP_URL_HOST),
            parse_url($mailMessage->actionText, PHP_URL_HOST)
        );
        $this->assertEquals(
            parse_url($notificationMail->actionText, PHP_URL_PATH),
            parse_url($mailMessage->actionText, PHP_URL_PATH)
        );
    }
}
