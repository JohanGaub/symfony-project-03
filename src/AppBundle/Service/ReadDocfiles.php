<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\File;
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
    }

    /**
     * Function to read dir content and set it
     * @return array
     */
    public function getDirContent()
    {
        $this->getAllowDirPaths();

        foreach ($this->dirPaths as $dirPath) {
            if ($dir = opendir($dirPath)) {
                while (false !== ($file = readdir($dir))) {
                    if ($file != '.' && $file != '..' && substr($file, 0, 1) != '.') {
                        dump(substr($file, 0, 1));
                        $temp['name']   = $file;
                        $pathArray      = explode('/', $dirPath);
                        $key            = $pathArray[count($pathArray) - 2];

                        $this->files[$key][] = [
                            'name'  => $file,
                            'key'   => $key
                        ];
                    }
                }
            }
        }
        return $this->files;
    }

    /**
     * Function to download  file (verification about download)
     * @param $type
     * @param $name
     * @return File
     */
    public function downloadFile($type, $name)
    {
        if ($this->security->isGranted('ROLE_PROJECT_RESP')
        || ($this->security->isGranted('ROLE_TECHNICIAN') && $type == 'technical')
        || ($this->security->isGranted('ROLE_COMMERCIAL') && $type == 'commercial'))
            return new File($this->docfiles . '/' . $type . '/' . $name);
        else
            throw new Exception('Le fichier n\'a pas été trouvé ou vous n\'avez pas accès à celui-ci. Merci de contacter un administrateur.');
    }

    /**
     * Check authorization for dynamic content
     * @return $this
     */
    private function getAllowDirPaths()
    {
        if ($this->security->isGranted('ROLE_PROJECT_RESP')) {
            $this->dirPaths[] = $this->docfiles . '/commercial/';
            $this->dirPaths[] = $this->docfiles . '/technical/';
        } else if ($this->security->isGranted('ROLE_COMMERCIAL')) {
            $this->dirPaths[] = $this->docfiles . '/commercial/';
        } else if ($this->security->isGranted('ROLE_TECHNICIAN')) {
            $this->dirPaths[] = $this->docfiles . '/technical/';
        } else {
            throw new Exception('Vous n\'avez pas accès à cette partie pour le moment. Merci de contacter un administrateur.');
        }
        return $this;
    }
}