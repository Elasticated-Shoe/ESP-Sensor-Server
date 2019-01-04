<?php

/* dashboard.twig */
class __TwigTemplate_08c1166d2cb8f2d69293d8a9719b9f46b7bd66d6c9a23bb0c32a1c2cdb7c2868 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("layouts/base.twig", "dashboard.twig", 1);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layouts/base.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "\t<div class=\"grid-container full\">
        <div id=\"readingContainer\" class=\"grid-x\">

        </div>
    </div>
";
    }

    public function getTemplateName()
    {
        return "dashboard.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  35 => 4,  32 => 3,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "dashboard.twig", "C:\\Users\\jmetcalfe3\\Documents\\GitHub\\ESP-Sensor-Server\\public_html\\protected\\views\\dashboard.twig");
    }
}
