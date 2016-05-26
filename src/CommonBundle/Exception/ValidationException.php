<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 20.02.16
 * Time: 20:46
 */

namespace CommonBundle\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends \Exception
{
    /**
     * @var ConstraintViolationListInterface
     */
    private $violations;

    /**
     * ValidationException constructor.
     *
     * @param ConstraintViolationListInterface $violations
     */
    public function __construct(ConstraintViolationListInterface $violations)
    {
        $this->violations = $violations;
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getViolations()
    {
        return $this->violations;
    }
}