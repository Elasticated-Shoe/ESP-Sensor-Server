<?php

/* partial/recentReadings.twig */
class __TwigTemplate_df931a5ddca90a8275391aff810f8b1055c0b13d67d2e3c74272c04cd329cc1e extends Twig_Template
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
        echo "<script type=\"text/template\" id=\"recentReadingsTemplate\">
    <% for(sensor in data) { %>
        <div class=\"small-6 medium-1 grid-x\">
            <p><%= sensor + \": \" + data[sensor]['reading'] %></p>
        </div>
    <% } %>
</script>";
    }

    public function getTemplateName()
    {
        return "partial/recentReadings.twig";
    }

    public function getDebugInfo()
    {
        return array (  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "partial/recentReadings.twig", "C:\\Users\\jmetcalfe3\\Documents\\GitHub\\ESP-Sensor-Server\\public_html\\protected\\views\\partial\\recentReadings.twig");
    }
}
