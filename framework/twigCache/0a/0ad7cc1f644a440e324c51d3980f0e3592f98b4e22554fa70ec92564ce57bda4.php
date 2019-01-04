<?php

/* layouts/base.twig */
class __TwigTemplate_71b66b80b8032cc7dee04d24804a4945c0d17fe4ef50dea8225064d60b8f6de0 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!doctype html>
<html class=\"no-js\" lang=\"en\" dir=\"ltr\">
    <head>
        ";
        // line 4
        echo twig_include($this->env, $context, "partial/head.twig");
        echo "
    </head>
    <body>
        <div class=\"grid-y body-grid\">
            <div id=\"header-container\" class=\"cell shrink\">
                ";
        // line 9
        echo twig_include($this->env, $context, "partial/header.twig");
        echo "
            </div>  
            <div id=\"content-container\" class=\"cell shrink\">
                ";
        // line 12
        $this->displayBlock('content', $context, $blocks);
        // line 15
        echo "            </div>
            <div class=\"footer-container cell auto\">
                ";
        // line 17
        echo twig_include($this->env, $context, "partial/footer.twig");
        echo "
            </div>
        </div>
        <script src=\"assets/js/vendor/jquery.js\"></script>
        <script src=\"assets/js/vendor/what-input.js\"></script>
        <script src=\"assets/js/vendor/slick.min.js\"></script>
        <script src=\"assets/js/vendor/underscore.js\"></script>
        <script src=\"assets/js/vendor/fuse.js\"></script>
        <script src=\"assets/js/vendor/foundation.min.js\"></script>
        <script src=\"assets/js/app.js\"></script>
        ";
        // line 27
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["scripts"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["script"]) {
            // line 28
            echo "            <script src=\"";
            echo twig_escape_filter($this->env, $context["script"], "html", null, true);
            echo "\"></script>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['script'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 30
        echo "        ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["templates"] ?? null));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["template"]) {
            // line 31
            echo "            ";
            echo twig_include($this->env, $context, $context["template"]);
            echo "
        ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['template'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 33
        echo "    </body>
</html>";
    }

    // line 12
    public function block_content($context, array $blocks = array())
    {
        // line 13
        echo "                    There is no content available for this page yet
                ";
    }

    public function getTemplateName()
    {
        return "layouts/base.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  118 => 13,  115 => 12,  110 => 33,  93 => 31,  75 => 30,  66 => 28,  62 => 27,  49 => 17,  45 => 15,  43 => 12,  37 => 9,  29 => 4,  24 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "layouts/base.twig", "C:\\Users\\jmetcalfe3\\Documents\\GitHub\\ESP-Sensor-Server\\public_html\\protected\\views\\layouts\\base.twig");
    }
}
