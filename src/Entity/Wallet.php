<?php

namespace App\Entity;

use App\Repository\WalletRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WalletRepository::class)]
class Wallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $account;

    #[ORM\Column(type: 'float')]
    private $balance;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updatedAt;

    #[ORM\OneToMany(mappedBy: 'wallet', targetEntity: WalletOperation::class, orphanRemoval: true)]
    private $walletOperations;

    public function __construct()
    {
        $this->walletOperations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, WalletOperation>
     */
    public function getWalletOperations(): Collection
    {
        return $this->walletOperations;
    }

    public function addWalletOperation(WalletOperation $walletOperation): self
    {
        if (!$this->walletOperations->contains($walletOperation)) {
            $this->walletOperations[] = $walletOperation;
            $walletOperation->setWallet($this);
        }

        return $this;
    }

    public function removeWalletOperation(WalletOperation $walletOperation): self
    {
        if ($this->walletOperations->removeElement($walletOperation)) {
            // set the owning side to null (unless already changed)
            if ($walletOperation->getWallet() === $this) {
                $walletOperation->setWallet(null);
            }
        }

        return $this;
    }
}
