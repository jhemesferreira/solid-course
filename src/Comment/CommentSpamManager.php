<?php

namespace App\Comment;

use App\Entity\Comment;
use App\Comment\CommentSpamCounterInterface;

class CommentSpamManager
{
    private CommentSpamCounterInterface $spamWordCounter;

    public function __construct(CommentSpamCounterInterface $spamWordCounter)
    {
        $this->spamWordCounter = $spamWordCounter;
    }
    
    public function validate(Comment $comment): void
    {
        $content = $comment->getContent();
        $badWordsOnComment = $this->spamWordCounter->countSpamWords($content);

        if ($badWordsOnComment >= 2) {
            throw new \Exception('Message detected as spam');
        }
    }
}
