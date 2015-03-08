<?php

namespace Songbird\Document\Repository;

use DomainException;
use JamesMoss\Flywheel\DocumentInterface;

/**
 * Repository
 *
 * Analageous to a table in a traditional RDBMS, a repository is a siloed
 * collection where documents live.
 */
class ReadOnlyRepository extends Repository
{
    /**
     * Store a Document in the repository.
     *
     * @param Document $document The document to store
     *
     * @return bool True if stored, otherwise false
     */
    public function store(DocumentInterface $document)
    {
        throw new DomainException('This repository does not implement the store method.');
    }

    /**
     * Store a Document in the repository, but only if it already
     * exists. The document must have an ID.
     *
     * @param Document $document The document to store
     *
     * @return bool True if stored, otherwise false
     */
    public function update(DocumentInterface $document)
    {
        throw new DomainException('This repository does not implement the store method.');
    }

    /**
     * Delete a document from the repository using its ID.
     *
     * @param mixed $id The ID of the document (or the document itself) to delete
     *
     * @return boolean True if deleted, false if not.
     */
    public function delete($id)
    {
        throw new DomainException('This repository does not implement the store method.');
    }
}