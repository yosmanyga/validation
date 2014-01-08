<?php

namespace Yosmanyga\Validation\Resource\Normalizer\XmlFile;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Normalizer\Common\ValueNormalizer as CommonValueNormalizer;
use Yosmanyga\Resource\Util\XmlKit;

class ValueNormalizer extends CommonValueNormalizer
{
    /**
     * @var \Yosmanyga\Resource\Util\XmlKit
     */
    private $xmlKit;

    public function __construct($xmlKit = null)
    {
        $this->xmlKit = $xmlKit ?: new XmlKit();
    }

    /**
     * @inheritdoc
     */
    public function supports($data, Resource $resource)
    {
        $data = $data['value']['name'];

        return parent::supports($data, $resource);
    }

    /**
     * @inheritdoc
     */
    public function normalize($data, Resource $resource)
    {
        if (isset($data['value']['option'])) {
            $data['value'] = $this->xmlKit->extractContent($data['value']['option']);
        }

        return $this->createDefinition($data['value']);
    }
}
