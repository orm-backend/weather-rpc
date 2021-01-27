<?php
namespace App\Entities;

use Carbon\Carbon;

class History extends Entity implements \JsonSerializable
{

    /**
     *
     * @var float
     */
    private $temp;

    /**
     *
     * @var \Carbon\Carbon
     */
    private $dateAt;

    /**
     *
     * @return float|null
     */
    public function getTemp()
    {
        return $this->temp;
    }

    /**
     *
     * @return \Carbon\Carbon|null
     */
    public function getDateAt()
    {
        return $this->dateAt;
    }

    /**
     *
     * @param float $temp
     */
    public function setTemp(float $temp)
    {
        $this->temp = $temp;
    }

    /**
     *
     * @param \Carbon\Carbon $dateAt
     */
    public function setDateAt(Carbon $dateAt)
    {
        $this->dateAt = $dateAt;
    }

    public function getModelValidationRules(): array
    {
        return [
            'dateAt' => [
                'required',
                'date',
                'before-or-equal:yesterday',
                'unique:App\Entities\History,dateAt,' . $this->id
            ],
            'temp' => [
                'required',
                'numeric',
                'between:-60,60',
                'regex:/^\-?\d{1,2}(\.\d{1})?$/'
            ]
        ];
    }

    /**
     * 
     * {@inheritDoc}
     * @see \JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'date' => $this->dateAt,
            'temp' => (float) $this->temp
        ];
    }
}
