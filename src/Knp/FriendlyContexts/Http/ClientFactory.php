<?php

namespace Knp\FriendlyContexts\Http;


use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;

final class ClientFactory
{
    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var HttpClient
     */
    private $originalClient;

    /**
     * @var Plugin[]
     */
    private $plugins;

    /**
     * @var bool
     */
    private $clientChanged;

    public function __construct(HttpClient $client = null)
    {
        $this->originalClient = $client ?: HttpClientDiscovery::find();
        $this->client = $this->originalClient;
        $this->resetPlugins();
    }

    /**
     * Drop all registered plugins
     */
    public function resetPlugins()
    {
        $this->plugins = [new Plugin\ErrorPlugin()];
        $this->clientChanged = true;
    }

    /**
     * Adds a new httplug plugin
     *
     * @param Plugin $plugin
     * @return ClientFactory
     */
    public function addPlugin(Plugin $plugin)
    {
        $this->plugins[] = $plugin;
        $this->clientChanged = true;

        return $this;
    }

    /**
     * @return HttpClient
     */
    public function getClient()
    {
        if (!$this->clientChanged) {
            return $this->client;
        }

        return new PluginClient($this->originalClient, $this->plugins);
    }
}
