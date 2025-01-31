<?php

namespace App\Entity\Vendor;

use App\Entity\BaseTrait;
use App\Repository\Vendor\VendorMessageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class VendorMessage
 * @package App\Entity\Vendor
 */
#[ORM\Table(name: 'vendor_message')]
#[ORM\Index(columns: ['slug'], name: 'vendorMessage_idx')]
#[ORM\Entity(repositoryClass: VendorMessageRepository::class)]
#[ORM\HasLifecycleCallbacks]
class VendorMessage
{
    use BaseTrait;

    #[ORM\Column(type: 'text')]
    private mixed $messageMine;

    #[ORM\ManyToOne(targetEntity: Vendor::class, inversedBy: 'vendorMessage')]
    private Vendor $vendorMessage;

    #[ORM\ManyToOne(targetEntity: VendorConversation::class, inversedBy: 'vendorConversationMessage')]
    private VendorConversation $vendorMessageConversation;

    #
    public function getMessageMine(): mixed
    {
        return $this->messageMine;
    }
    public function setMessageMine($messageMine): void
    {
        $this->messageMine = $messageMine;
    }
    # ManyToOne
    public function getVendorMessageConversation(): VendorConversation
    {
        return $this->vendorMessageConversation;
    }
    public function setVendorMessageConversation(VendorConversation $vendorMessageConversation): void
    {

        $this->vendorMessageConversation = $vendorMessageConversation;
    }
    # ManyToOne
    public function getVendorMessage(): Vendor
    {
        return $this->vendorMessage;
    }
    public function cetVendorMessage(Vendor $vendorMessage): void
    {
            $this->vendorMessage = $vendorMessage;
    }
    #


}
