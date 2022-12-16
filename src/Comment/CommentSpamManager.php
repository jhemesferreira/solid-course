<?php

namespace App\Comment;

use App\Entity\Comment;
use App\Service\RegexSpamWordHelper;

class CommentSpamManager
{
    private RegexSpamWordHelper $spamWordHelper;

    public function __construct(RegexSpamWordHelper $spamWordHelper)
    {
        $this->spamWordHelper = $spamWordHelper;
    }
    
    public function validate(Comment $comment): void
    {
        $content = $comment->getContent();
        $badWordsOnComment = $this->spamWordHelper->getMatchedSpamWords($content);

        if (count($badWordsOnComment) >= 2) {
            throw new \Exception('Message detected as spam');
        }
    }
}
