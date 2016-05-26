<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 14.01.16
 * Time: 20:31
 */

namespace CommonBundle\Form\Handler;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Request;

class ApiRequestHandler implements RequestHandlerInterface
{
    /**
     * Submits a form if it was submitted.
     *
     * @param FormInterface $form    The form to submit.
     * @param mixed         $request The current request.
     */
    public function handleRequest(FormInterface $form, $request = null)
    {
        if (!$request instanceof Request) {
            throw new UnexpectedTypeException(get_class($request), Request::class);
        }

        if ($request->getContentType() == 'json') {
            $content = $request->getContent();
            $data = json_decode($content, true);
        } else {
            $data = $request->request->all();
        }

        $form->submit($data);
    }
}