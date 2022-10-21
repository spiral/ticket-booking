<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: api/centrifugo/v1/message.proto

namespace Spiral\Shared\Services\Centrifugo\v1\DTO;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>api.centrifugo.v1.dto.BroadcastResult</code>
 */
class BroadcastResult extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>repeated .api.centrifugo.v1.dto.PublishResponse responses = 1;</code>
     */
    private $responses;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Spiral\Shared\Services\Centrifugo\v1\DTO\PublishResponse[]|\Google\Protobuf\Internal\RepeatedField $responses
     * }
     */
    public function __construct($data = NULL) {
        \Spiral\Shared\Services\Centrifugo\v1\GPBMetadata\Message::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>repeated .api.centrifugo.v1.dto.PublishResponse responses = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getResponses()
    {
        return $this->responses;
    }

    /**
     * Generated from protobuf field <code>repeated .api.centrifugo.v1.dto.PublishResponse responses = 1;</code>
     * @param \Spiral\Shared\Services\Centrifugo\v1\DTO\PublishResponse[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setResponses($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Spiral\Shared\Services\Centrifugo\v1\DTO\PublishResponse::class);
        $this->responses = $arr;

        return $this;
    }

}
