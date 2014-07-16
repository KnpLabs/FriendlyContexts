<?php

namespace Knp\FriendlyContexts\Builder;

use Guzzle\Http\ClientInterface;

interface RequestBuilderInterface
{
    public function build($uri = null, array $queries = null, array $headers = null, array $postBody = null, $body = null, array $options = []);

    public function setClient(ClientInterface $client = null);

    public function getClient();
}
