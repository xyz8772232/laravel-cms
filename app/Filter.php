<?php

namespace App;

use Encore\Admin\Grid\Filter\AbstractFilter;
use Illuminate\Support\Facades\Input;
use ReflectionClass;





/**
 * Class Filter
 *
 * @package \App
 */
class Filter
{
    /**
     * @var Model
     */
    protected $model;
    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var array
     */
    protected $supports = ['is', 'like', 'gt', 'lt', 'between', 'where', 'in'];

    /**
     * @var bool
     */
    protected $useModal = false;

    /**
     * If use id filter.
     *
     * @var bool
     */
    protected $useIdFilter = true;

    /**
     * Create a new filter instance.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {

        $this->model = $model;

        $this->is($this->model->eloquent()->getKeyName());
    }

    /**
     * Use modal to show filter form.
     */
    public function useModal()
    {
        $this->useModal = true;
    }

    /**
     * Disable Id filter.
     */
    public function disableIdFilter()
    {
        $this->useIdFilter = false;
    }

    /**
     * Get all conditions of the filters.
     *
     * @return array
     */
    public function conditions()
    {
        $inputs = array_filter(Input::all(), function ($input) {
            return $input !== '';
        });

        $conditions = [];

        foreach ($this->filters() as $filter) {
            $conditions[] = $filter->condition($inputs);
        }

        return array_filter($conditions);
    }

    /**
     * Add a filter to grid.
     *
     * @param AbstractFilter $filter
     *
     * @return AbstractFilter
     */
    protected function addFilter(AbstractFilter $filter)
    {
        return $this->filters[] = $filter;
    }

    /**
     * Get all filters.
     *
     * @return AbstractFilter[]
     */
    protected function filters()
    {
        return $this->filters;
    }

    /**
     * Execute the filter with conditions.
     *
     * @return array
     */
    public function execute()
    {
        $this->model->addConditions($this->conditions());

        return $this->model->buildData();
    }

    /**
     * Generate a filter object and add to grid.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return $this
     */
    public function __call($method, $arguments)
    {
        if (in_array($method, $this->supports)) {
            $className = '\\Encore\\Admin\\Grid\\Filter\\'.ucfirst($method);
            $reflection = new ReflectionClass($className);

            return $this->addFilter($reflection->newInstanceArgs($arguments));
        }
    }

}
