<?php

namespace Songbird\Document\Repository;

use Exception;

/**
 * NestedRepository
 */
class NestedRepository extends ReadOnlyRepository
{
    const SEPERATOR = '/';

    /**
     * Get the filesystem path for a document based on it's ID.
     *
     * @param string $id The ID of the document.
     *
     * @return string The full filesystem path of the document.
     * @throws \Exception
     */
    public function getPathForDocument($id)
    {
        if (!$this->validateId($id)) {
            throw new Exception(sprintf('`%s` is not a valid ID.', $id));
        }

        return $this->path . DIRECTORY_SEPARATOR . $this->getFilename($id);
    }

    /**
     * Checks to see if a document ID is valid
     *
     * @param  string $id The ID to check
     *
     * @return bool     True if valid, otherwise false
     */
    protected function validateId($id)
    {
        // Similar regex to the one in the parent method, this allows forward slashes
        // in the key name, except for at the start or end.
        return (boolean) preg_match('/^[^\\/]?[^\\?\\*:;{}\\\\\\n]+[^\\/]$/us', $id);
    }

    /**
     * Gets just the filename for a document based on it's ID.
     *
     * @param string $id The ID of the document.
     *
     * @return string The filename of the document, including extension.
     */
    public function getFilename($id)
    {
        return basename($id) . '.' . $this->formatter->getFileExtension();
    }

    /**
     * Get an array containing the path of all files in this repository
     *
     * @return array An array, item is a file path.
     */
    public function getAllFiles()
    {
        $ext = $this->formatter->getFileExtension();

        $files = array();
        $this->getFilesRecursive($this->path, $files, $ext);

        return $files;
    }

    protected function getFilesRecursive($dir, array &$result, $ext)
    {
        $extensionLength = strlen($ext) + 1; // one is for the dot!
        $files = $this->getFilesystem()->listContents($this->getName(), true);

        foreach ($files as $file) {
            if ($file['type'] === 'dir') {
                continue;
            }

            if (substr($file['path'], -$extensionLength) !== '.' . $ext) {
                continue;
            }

            $result[] = $file['path'];

        }

        return $result;
    }

    /**
     * @inherit
     */
    protected function getIdFromPath($path, $ext)
    {
        return substr($path, strlen($this->getName()) + 1, -strlen($ext) - 1);
    }

}
