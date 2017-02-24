<?php
namespace RAD\YellowCube\Soap\Util;

/**
 * Class SimpleValue
 */
abstract class SimpleValue
{
    /**
     * @var mixed $_
     */
    protected $_ = null;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->_ = $value;
    }

    /**
     *
     * @return mixed
     */
    public function get()
    {
        return $this->_;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->_;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function set($value)
    {
        $this->_ = $value;

        return $this;
    }
}
