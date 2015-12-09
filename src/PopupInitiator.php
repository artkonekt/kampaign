<?php

namespace Artkonekt\Kampaign;

use Artkonekt\Kampaign\JsGenerator\JsGeneratorInterface;
use Closure;

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
class PopupInitiator
{
    //TODO: Find a better solution than using traits.
    use ImpressionLoaderTrait;

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
     * @param ImpressionsRepositoryInterface $impressionsRepository
     * @param JsGeneratorInterface           $jsGenerator
     */
    public function __construct(ImpressionsRepositoryInterface $impressionsRepository, JsGeneratorInterface $jsGenerator)
    {
        $this->impressionsRepository = $impressionsRepository;
        $this->jsGenerator = $jsGenerator;
    }

    public static function createDefault()
    {
        return new self(new CookieImpressionsRepository(), new \Artkonekt\Kampaign\JsGenerator\FancyboxAjax());
    }

    /**
     * Returns the JQuery snippet which should be rendered in every the page the campaign popup should appear.
     *
     * @param TrackableCampaign                     $campaign The campaign to be presented
     * @param int                                   $timeout  How many seconds after page load should the popup appear
     * @param Closure $callback
     *
     * @return string
     * @internal param string $url The URL which renders the popup's contents
     */
    public function getJquerySnippet(TrackableCampaign $campaign, $timeout, Closure $callback)
    {
        $impressions = $this->loadImpressionsFor($campaign);

        if (!$impressions->canBeIncreasedToday()) {
            return '';
        }

        /** @var Callback $callback */
        $url = $callback($campaign);

        $js = $this->jsGenerator->getScript($campaign->getTrackingId(), $url , $timeout);

        return "<script>$js</script>";
    }
}