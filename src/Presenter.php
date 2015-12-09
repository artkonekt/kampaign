<?php

namespace Artkonekt\Kampaign;

use Artkonekt\Kampaign\JsGenerator\JsGeneratorInterface;

/**
 * Contains class CampaignPresenter
 *
 * @package     ${NAMESPACE}
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-09
 * @version     2015-12-09
 */
class Presenter
{
    /**
     * @var TrackableCampaign
     */
    private $campaign;

    /**
     * @var int
     */
    private $timeout;

    /**
     * @var string
     */
    private $url;

    /**
     * @var ImpressionsRepositoryInterface
     */
    private $impressionsRepository;

    /**
     * @var JsGeneratorInterface
     */
    private $jsGenerator;

    /**
     * CampaignPresenter constructor.
     *
     * @param TrackableCampaign              $campaign The campaign to be presented
     * @param int                            $timeout  How many seconds after page load should the popup appear
     * @param string                         $url      The URL which renders the popup's contents
     * @param ImpressionsRepositoryInterface $impressionsRepository
     * @param JsGeneratorInterface           $jsGenerator
     */
    public function __construct(
        TrackableCampaign $campaign,
        $timeout,
        $url,
        ImpressionsRepositoryInterface $impressionsRepository,
        JsGeneratorInterface $jsGenerator
    )
    {
        $this->url = $url;
        $this->campaign = $campaign;
        $this->timeout = $timeout;
        $this->impressionsRepository = $impressionsRepository;
        $this->jsGenerator = $jsGenerator;
    }

    /**
     * Loads the impressions object for the campaign from the repository, if it doesn't yet exists, it creates it.
     *
     * TODO: separate into its own component, or do the creation in the repo (probably not a good idea)?
     *
     * @return Impressions
     */
    private function loadImpressions()
    {
        $impressions = $this->impressionsRepository->findImpressionsByCampaign($this->campaign);

        if (!$impressions) {
            $impressions = new Impressions($this->campaign, 0, 0, true);
            $this->impressionsRepository->save($impressions);
        }

        return $impressions;
    }

    /**
     * Returns the JQuery snippet which should be rendered in every the page the campaign popup should appear.
     *
     * @return string
     */
    public function getJquerySnippet()
    {
        $impressions = $this->loadImpressions();

        if (!$impressions->canBeIncreasedToday()) {
            return '';
        }

        $js = $this->jsGenerator->getScript($this->campaign->getTrackingId(), $this->url, $this->timeout);

        return "<script>$js</script>";
    }
}