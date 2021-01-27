<?php
namespace App\Entities;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Illuminate\Support\Facades\Validator;

abstract class Entity
{
    /**
     * 
     * @var integer
     */
    protected $id;
    
    /**
     * Get id.
     *
     * @return integer|null
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Get validation rules
     * @return array
     */
    abstract public function getModelValidationRules() : array;
    
    /**
     *
     * @param \Doctrine\Common\Persistence\Event\LifecycleEventArgs $event
     */
    public function onBeforeAdd(LifecycleEventArgs $event)
    {
        $this->validate();
    }
    
    /**
     *
     * @param \Doctrine\Common\Persistence\Event\LifecycleEventArgs $event
     */
    public function onBeforeUpdate(LifecycleEventArgs $event)
    {
        $this->validate();
    }
    
    protected function validate()
    {
        $attributes = [];
        $rules = $this->getModelValidationRules();
        $fields = array_keys($rules);
        
        foreach ($fields as $field) {
            $method = 'get' . ucfirst($field);
            
            if (!method_exists($this, $method)) {
                $method = 'is' . ucfirst($field);
            }
            
            $attributes[$field] = $this->$method();
        }
        
        $validator = Validator::make($attributes, $rules);
        $validator->validate();
    }
}
