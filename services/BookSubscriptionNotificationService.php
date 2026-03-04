<?php

namespace app\services;

use app\models\Book;
use yii\db\Query;

class BookSubscriptionNotificationService
{
    private SmsPilotService $smsPilotService;

    public function __construct(
        SmsPilotService $smsPilotService
    ) {
        $this->smsPilotService = $smsPilotService;
    }

    public function notifyAboutNewBook(Book $book): void
    {
        if ($book->author_ids === []) {
            return;
        }

        $phones = (new Query())
            ->select('phone')
            ->from('subscription')
            ->where(['author_id' => $book->author_ids])
            ->distinct()
            ->column();

        if ($phones === []) {
            return;
        }

        $message = $this->buildMessage($book);

        foreach ($phones as $phone) {
            $this->smsPilotService->send((string) $phone, $message);
        }
    }

    private function buildMessage(Book $book): string
    {
        return 'Новая книга: ' . $book->title;
    }
}