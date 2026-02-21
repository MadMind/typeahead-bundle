<?php

namespace Lifo\TypeaheadBundle\Twig\Extension;

use Lifo\TypeaheadBundle\Form\Type\TypeaheadType;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TypeaheadTwigExtension extends AbstractExtension
{
    public function getName(): string
    {
        return 'lifo_typeahead';
    }

    public function getFunctions(): array
    {
        return array(
            new TwigFunction('lifo_typeahead_init', array($this, 'initTypeaheadFunction'), array('needs_environment' => true, 'is_safe' => array('html'))),
        );
    }

    public function initTypeaheadFunction(Environment $env): string
    {
        if (!TypeaheadType::$initialized) {
            TypeaheadType::$initialized = true;
            $url = $env->getExtension(AssetExtension::class)->getAssetUrl('bundles/lifotypeahead/js/typeaheadbundle.js');
            return "<script type=\"text/javascript\" src=\"$url\"></script>";
        }
        return '';
    }
}
