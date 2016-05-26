<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 29.02.16
 * Time: 0:14
 */

namespace CommonBundle\Controller;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;

class Controller extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
    /**
     * @param string $prefix
     * @param Form   $form
     * @param        $errors
     *
     * @return array
     */
    protected function collectErrors($prefix, FormInterface $form, &$errors)
    {
        foreach ($form->getErrors() as $error) {
            $errors[$prefix][] = $error->getMessage();
        }

        foreach ($form as $child) {
            $this->collectErrors($prefix . '[' . $child->getName() . ']', $child, $errors);
        }

        return $errors;
    }

    /**
     * @param Form $form
     *
     * @return array
     */
    protected function getFormErrors(Form $form)
    {
        $this->collectErrors($form->getName(), $form, $errors);

        return $errors;
    }
}