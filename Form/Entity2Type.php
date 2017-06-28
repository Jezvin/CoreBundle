<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 21:49.
 */

namespace Umbrella\CoreBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;

/**
 * Class Entity2Type.
 */
class Entity2Type extends Choice2Type
{

    protected function buildTemplate($templateOption, array &$choices)
    {
        if ($this->templated) {
            return;
        }

        /** @var ChoiceView $choice */
        foreach ($choices as $idx => &$choice) {
            $template = (string)call_user_func($templateOption, $choice->data);
            $choice->attr['data-template'] = htmlspecialchars($template);
        }
        $this->templated = true;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return EntityType::class;
    }
}
