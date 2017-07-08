<?php

namespace Twitter\API\REST\Factory;

use Codebird\Codebird;

class CodebirdFactory
{
    /**
     * Builds a codebird instance
     *
     * @param  string $consumerKey
     * @param  string $consumerSecret
     * @return Codebird
     */
    public function build(
        $consumerKey,
        $consumerSecret
    ) {
        Codebird::setConsumerKey($consumerKey, $consumerSecret);
        return new Codebird();
    }
}
