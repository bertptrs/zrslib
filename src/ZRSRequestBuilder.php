<?php

namespace zrslib;


class ZRSRequestBuilder
{
    const DEFAULTS = [
        'selgebouw' => '_ALL_',
        'res_instantie' => '_ALL_',
        'zrssort' => 'aanvangstijd',
        'gebruiker' => '',
        'aanvrager' => '',
        'activiteit' => '',
        'submit' => 'Uitvoeren',
    ];

    private $config;

    public function __construct(array $parameters = [])
    {
        $this->config = array_merge(self::DEFAULTS, $parameters);
        $this->ensureDefaultDate();
    }

    private function ensureDefaultDate()
    {
        $now = new \DateTime();
        if (empty($this->config['day'])) {
            $this->config['day'] = $now->format('j');
        }

        if (empty($this->config['month'])) {
            $this->config['month'] = $now->format('n');
        }

        if (empty($this->config['year'])) {
            $this->config['year'] = $now->format('Y');
        }
    }

    public function withDate(\DateTimeInterface $date)
    {
        $this->config['day'] = $date->format('j');
        $this->config['month'] = $date->format('n');
        $this->config['year'] = $date->format('Y');

        return $this;
    }

    public function withBuilding($building)
    {
        $this->config['selgebouw'] = $building;

        return $this;
    }

    public function withOrganisation($organisation)
    {
        $this->config['res_instantie'] = $organisation;

        return $this;
    }

    public function build()
    {
        return $this->config;
    }
}