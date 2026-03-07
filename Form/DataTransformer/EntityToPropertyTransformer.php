<?php

namespace Lifo\TypeaheadBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

class EntityToPropertyTransformer implements DataTransformerInterface
{
    protected $unitOfWork;
    protected $accessor;

    public function __construct(protected EntityManager $em, protected string $className, protected string $property = 'id')
    {
        $this->unitOfWork = $this->em->getUnitOfWork();
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    public function transform(mixed $value): mixed
    {
        if (empty($value)) {
            return null;
        }

        if ($this->className) {
            if (!empty($this->property)) {
                return $this->accessor->getValue($value, $this->property);
            } else {
                return current($this->unitOfWork->getEntityIdentifier($value));
            }
        }

        return $value;
    }


    public function reverseTransform(mixed $value): mixed
    {
        if (empty($value)) {
            return null;
        }

        if ($this->className) {
            $repo = $this->em->getRepository($this->className);
            if (!empty($this->property)) {
                return $repo->findOneBy(array($this->property => $value));
            } else {
                return $repo->find($value);
            }
        }

        return $value;
    }
}
