<?php

namespace App\Dto\Vendor;

use ApiPlatform\Metadata\ApiResource;
use App\Dto\Abstraction\ObjectDTO;
use App\Interface\Object\ObjectApiResourceInterface;
use Doctrine\Common\Collections\Collection;

#[ApiResource(mercure: true)]
final class VendorConversationDTO extends ObjectDTO implements ObjectApiResourceInterface
{
    private Collection $vendorConversationMessageDTO;

    private Collection $vendorConversationVendorDTO;
}