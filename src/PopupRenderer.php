<?php
/**
 * Contains class PopupRenderer
 *
 * @package     Artkonekt\Kampaign
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-12-09
 * @version     2015-12-09
 */

namespace Artkonekt\Kampaign;


use Artkonekt\Kampaign\Transformer\DebugTransformer;
use Artkonekt\Kampaign\Transformer\TransformerInterface;

class PopupRenderer
{
    //TODO: Find a better solution than using traits.
    use ImpressionLoaderTrait;

    /**
     * @var ImpressionsRepositoryInterface
     */
    private $impressionsRepository;

    /**
     * @var TransformerInterface
     */
    private $transformer;

    /**
     * PopupRenderer constructor.
     *
     * @param ImpressionsRepositoryInterface $impressionsRepository
     * @param TransformerInterface           $transformer
     */
    public function __construct(ImpressionsRepositoryInterface $impressionsRepository, TransformerInterface $transformer = null)
    {
        $this->transformer = $transformer;
        $this->impressionsRepository = $impressionsRepository;
    }

    public static function createDefault($debug = false)
    {
        $transformer = $debug ? new DebugTransformer() : null;
        return new PopupRenderer(new CookieImpressionsRepository(), $transformer);
    }

    /**
     * @param TrackableCampaign $campaign
     *
     * @param string            $template
     *
     * @return bool|string
     */
    public function render(TrackableCampaign $campaign, $template)
    {
        $impressions = $this->loadImpressionsFor($campaign);

        if (!$impressions->hasRemainingForToday()) {
            return false;
        }

        $impressions->increment();
        $this->impressionsRepository->save($impressions);

        if ($this->transformer) {
            return $this->transformer->transform($campaign, $impressions, $template);
        } else {
            return $template;
        }
    }
}