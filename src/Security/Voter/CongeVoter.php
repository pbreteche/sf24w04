<?php

namespace App\Security\Voter;

use App\Entity\Conge;
use App\Services\CalendarInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CongeVoter extends Voter
{
    public const EDIT = 'CONGE_EDIT';
    public const VIEW = 'CONGE_VIEW';
    public const DELETE = 'CONGE_DELETE';

    public function __construct(
        private readonly Security $security,
        private readonly CalendarInterface $calendar,
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof Conge;
    }

    /** @param Conge $subject */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        return match ($attribute) {
            self::EDIT =>
                !$this->calendar->isWeekend()
                && (
                    $this->security->isGranted('ROLE_ADMIN')
                    || $subject->getBenefciaire() === $user
                ),
            self::VIEW => $this->security->isGranted('ROLE_USER'),
            self::DELETE => $this->security->isGranted('ROLE_ADMIN') && $subject->getBenefciaire() === $user,
            default => false,
        };
    }
}
