<?php
namespace src\Services;

use Exception;

use function src\Controller\dd;

class FileService
{
    private $files;

    /**
     * @var array
     */
    private const EXTENSIONS = ['jpg', 'gif', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'txt', 'csv', ''];

    /**
     * @var array
     */
    private const EXTENSIONS_IMAGE = ['jpg', 'gif', 'png', 'jpeg'];

    /**
     * @var array
     */
    private const EXTENSIONS_DOC = ['doc', 'docx', 'pdf', 'txt', 'csv', 'ppt', 'pptx', 'zip', 'rar', 'jpg', 'gif', 'png', 'jpeg'];

    /**
     * @var string
     */
    private const DIRECTORY = '../assets';

    /**
     * @var array
     */
    private const SIZE = ['K' => 1000, 'M' => 1000000, 'G' => 1000000000, 'T' => 1000000000000];

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $error;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $real_name_doc;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $extension;

    /**
     * @var string
     */
    private $path;

    /**
     * @var bool
     */
    private $can_save;

    /**
     * @var float
     */
    private $size_limit;

    /**
     * @var string
     */
    private $unit;

    /**
     * @var bool
     */
    private $local;

    public function __construct($files, string $type, $path = 'chat', bool $save = false, int $size_limit = null, $unit = 'K', $local = false)
    {
        $this->path = $path;
        $this->files = $files;
        $this->type = $type;
        $this->can_save = $save;
        $this->size_limit = null !== $size_limit ? $size_limit * self::SIZE[strtoupper($unit)] : $size_limit;
        $this->unit = $unit;
        $this->local = $local;
        $this->error = null;
    }

    public function setFiles($files): self
    {
        $this->files = $files;

        return $this;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function getDirectory(): string
    {
        return self::DIRECTORY; //. '/' . $this->rep;
    }

    public function setError($error): self
    {
        $this->error = $error;

        return $this;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setRealNameDoc(string $real_name_doc): self
    {
        $this->real_name_doc = $real_name_doc;

        return $this;
    }

    public function getRealNameDoc(): string
    {
        return $this->real_name_doc;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setExtension(string $filename): self
    {
        $this->extension = $filename;

        return $this;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getRoute(): string
    {
        return ($this->local == true ? str_replace('../', './', self::DIRECTORY) :  self::DIRECTORY) . '/' . $this->path;
    }

    public function saveFile()
    {
        if($this->can_save) {
            if ($this->files['error'] == 0)
            {
                if(null !== $this->size_limit && $this->files['size'] > $this->size_limit) {
                    $size = $this->size_limit / self::SIZE[$this->unit];
                    $this->setError('The weight of your file exceeds the size limit of ' . $size . $this->unit . '.');
                }
    
                if(null == $this->getError()) {
                    $infosfichier = pathinfo($this->files['name']);

                    $extension_upload = strtolower($infosfichier['extension']);
                    $accept = false;
                    if(strtolower($this->type) === 'doc') {
                        $accept = in_array($extension_upload, self::EXTENSIONS_DOC);
                    } elseif(strtolower($this->type) === 'image') {
                        $accept = in_array($extension_upload, self::EXTENSIONS_IMAGE);
                    }
        
                    if($accept) 
                    {
                        $name = md5(time()) . '.' . $extension_upload;
                        // $name = md5_file($this->files['name']);

                        $this->setName($name);
                        $this->setRealNameDoc($this->files['name']);
                        $this->setFilename($infosfichier['filename']);
                        $this->setExtension($infosfichier['extension']);
                        try {
                            move_uploaded_file($this->files['tmp_name'], $this->getRoute() . '/' . $name);
                            return true;
                        } catch(Exception $e) {
                            $this->setError($e->getMessage());
                        }
                    }
                    else
                    {
                        $this->setError('File extension not allowed.');
                    }
                }
            }
            else
            {
                $this->setError('Corrupted file.');
            }
        }
        return false;
    }



    public function saveFiles()
    {
        // if ($this->files['error'] == 0)
        {
            // if ($this->files['size'] <= 5000000)
            // {
                $infosfichier = pathinfo($this->files['name']);
                $extension_upload = strtolower($infosfichier['extension']);

                $err = false;

                if(strtolower($this->type) === 'doc') {
                    $err = in_array($extension_upload, self::EXTENSIONS_DOC);
                } elseif(strtolower($this->type) === 'image') {
                    $err = in_array($extension_upload, self::EXTENSIONS_IMAGE);
                }

                if($err) 
                {
                    $name = md5(time()) . '.' . $extension_upload;
                    $this->setName($name);
                    $this->setRealNameDoc($this->files['name']);
                    try {
                        move_uploaded_file($this->files['tmp_name'], self::DIRECTORY . '/' . $this->path. '/' . $name);
                        // move_uploaded_file($this->files['tmp_name'], self::DIRECTORY . '/' . $this->rep . '/' . $name);
                        return true;
                    } catch(Exception $e) {
                        $this->setError($e->getMessage());
                    }
                }
                else
                {
                    $this->setError('Extension not allowed.');
                }
            // } 
            // else 
            // {
            //     $this->setError('Le poids de votre fichier dépasse la taille limite de 5Mo.');
            // }
        }
        // else
        // {
        //     $this->setError('Fichier corrompu.');
        // }
        return false;
    }

    public function saveDoc()
    {
        if ($this->files['error'] == 0)
        {
            if ($this->files['size'] <= 5000000)
            {
                $infosfichier = pathinfo($this->files['name']);
                $extension_upload = strtolower($infosfichier['extension']);

                $err = false;

                if(strtolower($this->type) === 'doc') {
                    $err = in_array($extension_upload, self::EXTENSIONS_DOC);
                } elseif(strtolower($this->type) === 'image') {
                    $err = in_array($extension_upload, self::EXTENSIONS_IMAGE);
                }

                if($err) 
                {
                    $name = md5(time()) . '.' . $extension_upload;
                    $this->setName($name);
                    $this->setRealNameDoc($this->files['name']);
                    try {
                        move_uploaded_file($this->files['tmp_name'], self::DIRECTORY . '/' . $this->path. '/' . $name);
                        // move_uploaded_file($this->files['tmp_name'], self::DIRECTORY . '/' . $this->rep . '/' . $name);
                        return true;
                    } catch(Exception $e) {
                        $this->setError($e->getMessage());
                    }
                }
                else
                {
                    $this->setError('File extension not allowed.');
                }
            } 
            else 
            {
                $this->setError('The weight of your file exceeds the size limit of 5MB.');
            }
        }
        else
        {
            $this->setError('Corrupted file.');
        }
        return false;
    }
}