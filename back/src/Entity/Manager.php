<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\ManagerRepository;
use App\State\UserPasswordHasher;
use App\Utils\Constants\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups as ApiGroups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ManagerRepository::class)]
#[UniqueEntity('email')]
#[ApiResource(
    operations: [
        new Get(),
        new Get(
            uriTemplate: '/me',
            normalizationContext: [
                'groups' => [
                    Groups::GROUP_MANAGER_READ_ITEM,
                ],
            ],
        ),
        new GetCollection(
            normalizationContext: [
                'groups' => [
                    Groups::GROUP_MANAGER_READ_COLLECTION,
                ],
            ],
        ),
        new Post(
            validationContext: [
                'groups' => [
                    'Default',
                    Groups::GROUP_MANAGER_CREATE_ITEM,
                ],
            ],
            processor: UserPasswordHasher::class,
        ),
        new Patch(
            processor: UserPasswordHasher::class,
        ),
    ],
    normalizationContext: [
        'groups' => [
            Groups::GROUP_MANAGER_READ_ITEM,
        ],
    ],
    denormalizationContext: [
        'groups' => [
            Groups::GROUP_MANAGER_CREATE_ITEM,
            Groups::GROUP_MANAGER_UPDATE_ITEM,
        ],
    ],
    security: "is_granted('ROLE_ADMIN')",
)]
class Manager implements UserInterface, PasswordAuthenticatedUserInterface
{
    /** @var string */
    final public const ROLE_USER = 'ROLE_USER';

    /** @var string */
    final public const ROLE_ADMIN = 'ROLE_ADMIN';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiGroups([
        Groups::GROUP_MANAGER_READ_COLLECTION,
        Groups::GROUP_MANAGER_READ_ITEM,
    ])]
    protected ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Email cannot be blank')]
    #[ApiGroups([
        Groups::GROUP_MANAGER_READ_COLLECTION,
        Groups::GROUP_MANAGER_READ_ITEM,
        Groups::GROUP_MANAGER_CREATE_ITEM,
        Groups::GROUP_MANAGER_UPDATE_ITEM,
    ])]
    protected ?string $email = null;

    #[ORM\Column]
    protected ?string $password = null;

    #[Assert\NotBlank(
        groups: [
            Groups::GROUP_MANAGER_CREATE_ITEM,
        ],
    )]
    #[ApiGroups([
        Groups::GROUP_MANAGER_CREATE_ITEM,
        Groups::GROUP_MANAGER_UPDATE_ITEM,
    ])]
    protected ?string $plainPassword = null;

    #[ORM\Column]
    #[ApiGroups([
        Groups::GROUP_MANAGER_READ_COLLECTION,
        Groups::GROUP_MANAGER_READ_ITEM,
    ])]
    protected array $roles = [];

    #[ORM\OneToOne(
        mappedBy: 'manager',
        targetEntity: ManagerSettingsFeature::class,
        cascade: ['persist'],
    )]
    #[ApiGroups([
        Groups::GROUP_MANAGER_READ_ITEM,
        Groups::GROUP_MANAGER_UPDATE_ITEM,
    ])]
    protected ?ManagerSettingsFeature $managerSettingsFeature = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = self::ROLE_USER;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function getManagerSettingsFeature(): ManagerSettingsFeature
    {
        return
            $this->managerSettingsFeature ?? (new ManagerSettingsFeature())
                ->setManager($this)
        ;
    }

    public function setManagerSettingsFeature(
        ManagerSettingsFeature $managerSettingsFeature,
    ): self {
        $this->managerSettingsFeature = $managerSettingsFeature;
        $managerSettingsFeature->setManager($this);

        return $this;
    }
}
