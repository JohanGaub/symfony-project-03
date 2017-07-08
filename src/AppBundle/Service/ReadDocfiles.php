<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

/**
 * Class ReadDocfiles
 * @package AppBundle\Service
 */
class ReadDocfiles
{
    const COMMERCIAL_DIR = '/commercial/';
    const TECHNCIAL_DIR = '/technical/';

    /** @var AuthorizationChecker */
    private $security;

    /** @var User */
    private $user;

    /** @var string */
    private $docfiles;

    /** @var array */
    private $dirPaths = [];

    /** @var array */
    private $files = [];

    /**
     * ReadDocfiles constructor.
     * @param AuthorizationChecker $security
     * @param TokenStorage $tokenStorage
     * @param string $docfiles
     */
    public function __construct(AuthorizationChecker $security, TokenStorage $tokenStorage, string $docfiles)
    {
        $this->user     = $tokenStorage->getToken()->getUser();
        $this->security = $security;
        $this->docfiles = $docfiles;
        $this->checkAuthorization();
    }

    /**
     * Function to read dir content and set it
     * @return array
     */
    public function getDirContent()
    {
        foreach ($this->dirPaths as $dirPath) {
            if ($dir = opendir($dirPath)) {
                while (false !== ($file = readdir($dir))) {
                    if ($file != '.' && $file != '..') {
                        $temp['name']   = $file;
                        $temp['path']   = $dirPath . $file;
                        $keyArray       = explode('/', $dirPath);
                        $key            = $keyArray[count($keyArray) - 2];
                        $this->files[$key][] = ['name' => $file, 'path' => $dirPath . $file];
                    }
                }
            }
        }
        return $this->files;
    }

    /**
     * Check authorization for dynamic content
     * @return $this
     */
    private function checkAuthorization()
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $this->dirPaths[] = $this->docfiles . '/commercial/';
            $this->dirPaths[] = $this->docfiles . '/technical/';
        } else if ($this->security->isGranted('ROLE_COMMERCIAL')) {
            $this->dirPaths[] = $this->docfiles . '/commercial/';
        } else if ($this->security->isGranted('ROLE_TECHNICIAN')) {
            $this->dirPaths[] = $this->docfiles . '/technical/';
        }
        return $this;
    }
}