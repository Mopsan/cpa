<?php

namespace Artjoker\Cpa\Providers\PdlProfit;

use Artjoker\Cpa\Interfaces\Conversion\SendServiceInterface;
use Artjoker\Cpa\Interfaces\Lead\LeadSource;
use Artjoker\Cpa\Models\Conversion;
use Artjoker\Cpa\Traits\SendServiceTrait;
use GuzzleHttp\Psr7\Request;

class SendService implements SendServiceInterface
{
    use SendServiceTrait;

    public $source = LeadSource::PDL_PROFIT;

    /**
     * @var EnvironmentConfig
     */
    protected $config;

    /**
     * SendService constructor.
     * @param EnvironmentConfig $config
     */
    public function __construct(EnvironmentConfig $config)
    {
        $this->config = $config;
    }


    protected function getRequest(Conversion $conversion, array $params): Request
    {
        $clickId      = $conversion->getConfig()['click_id'] ?? null;
        $conversionId = $conversion->getId();

        $leadId     = $params['lead_id'] ?? null;
        $leadStatus = $params['lead_status'] ?? null;

        $queryParams = http_build_query([
            'click_id'       => $clickId,
            'transaction_id' => $conversionId,
            'lead_id'        => $leadId,
            'lead_status'    => $leadStatus,
        ]);

        $url = "{$this->getDomain()}/postback?{$queryParams}";

        return new Request('get', $url);
    }
}