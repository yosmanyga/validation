<?php

namespace Yosmanyga\Validation\Resource\Normalizer\XmlFile;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Resource\Util\XmlKit;
use Yosmanyga\Validation\Resource\Normalizer\Common\ObjectReferenceNormalizer as CommonObjectReferenceNormalizer;

class ObjectReferenceNormalizer extends CommonObjectReferenceNormalizer
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
     * {@inheritdoc}
     */
    public function supports($data, Resource $resource)
    {
        $data = $data['value']['name'];

        return parent::supports($data, $resource);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data, Resource $resource)
    {
        if (isset($data['value']['option'])) {
            $data['value'] = $this->xmlKit->extractContent($data['value']['option']);
        }

        return $this->createDefinition($data['value']);
    }
}
