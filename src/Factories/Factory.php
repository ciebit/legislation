<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Factories;

use Ciebit\Legislation\Document;
use Ciebit\Legislation\Constitution;
use Ciebit\Legislation\Decree;
use Ciebit\Legislation\Factories\Constitution as ConstitutionFactory;
use Ciebit\Legislation\Factories\Decree as DecreeFactory;
use Ciebit\Legislation\Factories\Law as LawFactory;
use Ciebit\Legislation\Factories\Ordinance as OrdinanceFactory;
use Ciebit\Legislation\Law;
use Ciebit\Legislation\Ordinance;
use Exception;

class Factory
{
    private array $data;
    private ?Document $document;
    private string $type;

    public function __construct()
    {
        $this->data = [];
        $this->document = null;
        $this->type = 'unknow';
    }

    public function create(): Document
    {
        switch($this->type) 
        {
            case Constitution::class:
                $factory = new ConstitutionFactory;
                break;
            case Decree::class:
                $factory = new DecreeFactory;
                break;
            case Law::class:
                $factory = new LawFactory;
                break;
            case Ordinance::class:
                $factory = new OrdinanceFactory;
                break;
            default:
                throw new Exception('ciebit.legislation.factories.type-not-found', 3);
        }

        if ($this->document instanceof Document) {
            $factory->setDocument($this->document);
        }
        $factory->setData($this->data);

        return $factory->create();
    }

    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function setDocument(Document $document): self
    {
        $this->document = $document;
        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }    
}