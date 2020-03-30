<?php
declare(strict_types = 1);

namespace Domain\Exception;

use Domain\Entity\Author;

final class AuthorAlreadyExist extends LogicException
{
    private Author $author;

    public function __construct(Author $author)
    {
        $this->author = $author;
    }

    /**
     * The author that already exist
     */
    public function author(): Author
    {
        return $this->author;
    }
}
