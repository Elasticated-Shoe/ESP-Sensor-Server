<?php

/* partial/head.twig */
class __TwigTemplate_d2dd5d9275987f34d8211a33f699266c764a5db2677923e4257076b77352809d extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<head>
    <meta charset=\"utf-8\">
    <meta http-equiv=\"x-ua-compatible\" content=\"ie=edge\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <link rel=\"stylesheet\" href=\"assets/css/foundation.min.css\">
    <link rel=\"stylesheet\" href=\"assets/css/slick.css\">
    <link rel=\"stylesheet\" href=\"assets/css/slick-theme.css\">
    <link rel=\"stylesheet\" href=\"assets/css/app.css\">
    <link rel=\"icon\" href=\"assets/images/favicon.ico\" />
    <title>";
        // line 10
        echo twig_escape_filter($this->env, ($context["pageHead"] ?? null), "html", null, true);
        echo "</title>
</head>";
    }

    public function getTemplateName()
    {
        return "partial/head.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  34 => 10,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "partial/head.twig", "C:\\Users\\jmetcalfe3\\Documents\\GitHub\\ESP-Sensor-Server\\public_html\\protected\\views\\partial\\head.twig");
    }
}
