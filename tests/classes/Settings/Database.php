<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Tests\Settings;

use function realpath;

class Database
{
    private string $charset;
    private string $documentTableName;
    private string $drive;
    private string $host;
    private string $password;
    private int $port;
    private string $name;
    private string $revogationTableName;
    private string $user;

    public function __construct()
    {
        $data = 
        $defaultData = [
            'charset' => 'utf8',
            'documentTableName' => 'cb_legislation_document',
            'drive' => 'mysql',
            'host' => 'localhost',
            'name' => 'cb_legislation',
            'password' => '',
            'port' => 3306,
            'revogationTableName' => 'cb_legislation_revogation',
            'user' => 'root',
        ];
        $pathFileSettings = realpath(__DIR__.'/../../settings.php');
        
        if (is_file($pathFileSettings)) {
            $data = array_merge($defaultData, (include $pathFileSettings)['database'] ?? []);
        }

        $this->charset = $data['charset'];
        $this->documentTableName = $data['documentTableName'];
        $this->drive = $data['drive'];
        $this->host = $data['host'];
        $this->name = $data['name'];
        $this->password = $data['password'];
        $this->port = $data['port'];
        $this->revogationTableName = $data['revogationTableName'];
        $this->user = $data['user'];
    }

    public function getCharset(): string
    {
        return $this->charset;
    }

    public function getDocumentTableName(): string
    {
        return $this->documentTableName;
    }

    public function getDrive(): string
    {
        return $this->drive;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRevogationTableName(): string
    {
        return $this->revogationTableName;
    }

    public function getUser(): string
    {
        return $this->user;
    }
}