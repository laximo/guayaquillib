<?php


namespace GuayaquilLib\objects;

use Exception;
use SimpleXMLElement;

abstract class BaseObject
{
    /**
     * @param SimpleXMLElement|null $data
     * @throws Exception
     */
    public function __construct(?SimpleXMLElement $data = null)
    {
        if (is_null($data)) {
            throw new Exception('Empty data');
        }

        $this->fromXml($data);
    }

    /**
     * @param SimpleXMLElement $data
     */
    abstract protected function fromXml(SimpleXMLElement $data);

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = [];
        foreach ($this as $key => $value) {
            if ($value instanceof BaseObject) {
                $data[$key] = $value->toArray();
            } elseif (is_array($value)) {
                $data[$key] = array_map(function ($item) {
                    return ($item instanceof BaseObject) ? $item->toArray() : $item;
                }, $value);
            } else {
                $data[$key] = $value;
            }
        }
        return $data;
    }
}